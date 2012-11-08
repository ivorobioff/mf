<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Numeric;
use \Controller\Common\Layout;
use \Model\Operations\Budget as ModelBudget;
use \Plugins\Validator\Validator;
use \System\Lib\Http;
use \Facade\Log\Log as FacadeLog;

class Budget extends Layout
{
	public function withdrawal()
	{
		$this->_doOperation('withdrawal');
	}

	public function deposit()
	{
		$this->_doOperation('deposit');
	}

	private function _doOperation($type)
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$validator_rules = array(
			'amount' => new Numeric('Сумма должна быть числом')
		);

		if (!Validator::isValid($data, $validator_rules))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$budget = ModelBudget::getInstance();

		switch ($type)
		{
			case 'withdrawal':
				$budget->withdrawal($data['amount']);
				break;
			case 'deposit':
				$budget->deposit($data['amount']);
				break;
			default:
				return $this->_sendError(array('Wrong operation.'));
				break;
		}

		FacadeLog::logIt(array(
			'item_name' => 'Budget',
			'amount' => $data['amount'],
			'app_comment' => $type == 'withdrawal' ? FacadeLog::AC_BUDGET_WITHDRAWAL : FacadeLog::AC_BUDGET_DEPOSIT
		));

		$this->_sendResponse($budget->getSummary());
	}
}