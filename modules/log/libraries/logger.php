<?php
namespace Lib\Log;

use \System\Mvc\Model as SystemModel;
use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Categories as ModelCategories;
use \Model\Log\Log as ModelLog;

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

	/**
	 * @var SystemModel
	 */
	private $_model;

	private $_operation;

	private $_fixed_data = array();

	public function __construct(SystemModel $model, $operation)
	{
		$this->_model = $model;
		$this->_operation = $operation;
	}

	public function fixBefore()
	{
		$this->_fixed_data['before'] = $this->_fix();
	}

	public function fixAfter()
	{
		$this->_fixed_data['after'] = $this->_fix();
	}

	public function finalize($amount, $comments = '')
	{
		$item_name = $this->_model instanceof ModelCategories ? $this->_model->getTitle() : 'Budget';

		$data = array(
			'amount' => $amount,
			'item_name' => $item_name,
			'operation' => $this->_operation,
			'fixation' => serialize($this->_fixed_data),
			'comments' => $comments,
		);

		$log = new ModelLog();
		return $log->log($data);
	}

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
}