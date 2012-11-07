<?php
namespace Facade\Log;

use \Model\Log\Log as ModelLog;

class Log
{
	const AC_BUDGET_WITHDRAWAL = 'Withdrawal';
	const AC_BUDGET_DEPOSIT = 'Deposite';

	const AC_CATEGORY_DEPOSIT = 'Deposite';
	const AC_CATEGORY_WITHDRAWAL = 'Withdrawal';
	const AC_REQUEST_AMOUNT = 'Request amount';
	const AC_RETURN_AMOUNT = 'Return amount';


	/**
	 * @var ModelLog
	 */
	static private $_model_log = null;

	static public function logIt(array $data)
	{
		if (self::$_model_log == null)
		{
			self::$_model_log = new ModelLog();
		}

		return self::$_model_log->logIt($data);
	}
}