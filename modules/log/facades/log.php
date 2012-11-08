<?php
namespace Facade\Log;

use \Model\Log\Log as ModelLog;
use \System\Mvc\Model as SystemModel;
use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Categories as ModelCategories;

class Log
{
	const AC_BUDGET_WITHDRAWAL = 'Withdrawal';
	const AC_BUDGET_DEPOSIT = 'Deposite';
	const AC_CATEGORY_DEPOSIT = 'Deposite';
	const AC_CATEGORY_WITHDRAWAL = 'Withdrawal';
	const AC_CHANGE_AMOUNT = 'Change amount';
	const AC_REQUEST_AMOUNT = 'Request amount';
	const AC_RETURN_AMOUNT = 'Return amount';

	/**
	 * @var ModelLog
	 */
	static private $_model_log = null;

	static function before(SystemModel $model, $amount, $type)
	{
		$data = self::_prepareData(ModelLog::POSITION_BEFORE, $model, $amount, $type);
		self::_log($data);
	}

	static function after($amount, $type)
	{
		$data = self::_prepareData(ModelLog::POSITION_AFTER, $model, $amount, $type);
		self::_log($data);
	}

	static private function _log(array $data)
	{
		if (self::$_model_log == null)
		{
			self::$_model_log = new ModelLog();
		}

		return self::$_model_log->log($data);
	}

	static private function _prepareData($position, SystemModel $model, $amount, $type)
	{
		$model_info = $model->get();

		$itme_name = $model instanceof ModelBudget ? 'Budget' : $model_info['title'];

		$data = array(
			'amount' => $amount,
			'item_name' => $item_name,
			'position' => $position,
			'budget' => ModelBudget::getInstance(),
			'category' => $model instanceof ModelCategories ? $model : null
		);

		return $data;
	}

}