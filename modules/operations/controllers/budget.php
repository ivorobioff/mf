<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Numeric;
use \Controller\Common\Layout;
use \Model\Operations\Budget as ModelBudget;
use \Plugins\Validator\Validator;
use \System\Lib\Http;

class Budget extends Layout
{
	public function withdrawal()
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

		$budget = new ModelBudget(1);

		$budget->withdrawal($data['amount']);

		$this->_sendResponse($budget->getStatistics());
	}
}