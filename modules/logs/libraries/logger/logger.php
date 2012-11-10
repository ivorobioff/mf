<?php
namespace Lib\Logs\Logger;

use \System\Mvc\Model as SystemModel;
use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Categories as ModelCategories;
use \Model\Logs\Logs as ModelLogs;
use \Lib\Logs\Logger\Exception as LogException;
use \Lib\Common\ErrorCodes;

/**
 * Класс для логирования операций
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Logger
{
	const AC_BUDGET_WITHDRAWAL = 'budget_withdrawal';
	const AC_BUDGET_DEPOSIT = 'budget_deposit';
	const AC_CATEGORY_WITHDRAWAL = 'category_withdrawal';
	const AC_CHANGE_AMOUNT = 'change_amount';
	const AC_REQUEST_AMOUNT = 'request_amount';
	const AC_RETURN_AMOUNT = 'return_amount';
	const AC_DELETE_CATEGORY = 'delete_category';
	const AC_CREATE_CATEGORY = 'create_category';

	const ERROR_NO_AFTER_FIXATION = 'Отсутствует фиксация после произведенной операции';
	const ERROR_NO_BEFORE_FIXATION = 'Отсутствует фиксация до произведения операции';
	const ERROR_WRONG_MODEL = 'Не верная модель';

	/**
	 * @var SystemModel
	 */
	private $_model;

	private $_operation;

	private $_fixed_data = array();

	public function __construct($model, $operation)
	{
		$this->_validateModel($model);
		$this->_model = $model;
		$this->_operation = $operation;
	}

	/**
	 * Задает модель.
	 * Используется в случае если в момент создания логера, логируемой модели еще нет.
	 */
	public function setModel($model)
	{
		$this->_validateModel($model);
		$this->_model = $model;
	}

	/**
	 * Возвращает текующею фиксацию
	 * @return array:
	 */
	public function getFixation()
	{
		return $this->_fixed_data;
	}

	/**
	 * Задает уже готовую фиксацию. Используется когда нет смысла использовать self::fixAfter и self::fixBefore
	 * @param array $data
	 */
	public function setFixation(array $data)
	{
		$this->_fixed_data = $data;
	}

	/**
	 * Фиксация состояния бюджета и категории (если операция производится над котегорией)
	 * до произведения операции.
	 */
	public function fixBefore()
	{
		$this->_fixed_data['before'] = $this->_fix();
	}

	/**
	 * Фиксация состояния бюджета и категории (если операция производится над котегорией)
	 * после того как операция была произведена.
	 * @return Logger
	 */
	public function fixAfter()
	{
		if (!isset($this->_fixed_data['before']))
		{
			throw new LogException(self::ERROR_NO_BEFORE_FIXATION);
		}

		$this->_fixed_data['after'] = $this->_fix();

		return $this;
	}

	/**
	 * Сохраняем лог
	 * @param int $amount
	 * @param string $comments
	 */
	public function finalize($amount, $comments = '')
	{
		if (!isset($this->_fixed_data['after']))
		{
			throw new LogException(self::ERROR_NO_AFTER_FIXATION);
		}

		$item_name = '-';

		if ($this->_model instanceof ModelCategories)
		{
			$item_name = $this->_model->getTitle();
		}

		if ($this->_model instanceof ModelBudget)
		{
			$item_name = 'Budget';
		}

		$data = array(
			'amount' => $amount,
			'item_name' => $item_name,
			'operation' => $this->_operation,
			'fixation' => serialize($this->_fixed_data),
			'comments' => $comments,
		);

		$log = new ModelLogs();
		return $log->log($data);
	}

	/**
	 * Фиксация
	 */
	private function _fix()
	{
		$fixation['budget'] = ModelBudget::getInstance()->getSummary();

		if ($this->_model instanceof ModelCategories)
		{
			$cat_info = $this->_model->get();

			$fixation['category'] = array(
				'amount' => $cat_info['amount'],
				'current_amount' => $cat_info['current_amount']
			);
		}

		return $fixation;
	}

	/**
	 * Создаем контракт для входящей модели
	 * @param mixed $model
	 * @throws LogicException
	 */
	private function _validateModel($model)
	{
		if (!is_null($model) && !$model instanceof SystemModel)
		{
			throw new LogicException(self::ERROR_WRONG_MODEL);
		}
	}
}