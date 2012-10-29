<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Emptiness;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use \System\Lib\Http;
use \Plugins\Utils\Massive;
use \Plugins\Validator\Validator;

class Flow extends \System\Mvc\Controller
{
	public function index()
	{
		$model_groups = new ModelGroups();
		$model_categories = new ModelCategories();

		$this->_view->groups = json_encode($model_groups->getAll());
		$this->_view->categories = json_encode($model_categories->getAll());

		$this->_view->render('operations/flow/index.phtml');
	}

	public function withdrawal()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$cat = new ModelCategories($data['id']);

		Massive::applyRules($data, array('amount' => function($value){  return sprintf('%0.2f', floatval($value)); } ));

		$validator = new Validator();

		$validator_rules = array('amount' => new Emptiness('Сумма должна быть больше 0.00'));

		//Маленький хак для валидатора, из-за того что php не воспринимает 0.00 как пустоту
		if (!$validator->isValid(array('amount' => floatval($data['amount'])), $validator_rules))
		{
			$this->_ajax_responder->sendError($validator->fetchErrors());

			return ;
		}

		if (($cat->getCurrentAmount() - $data['amount']) < 0)
		{
			$this->_ajax_responder->sendError(array('zero' => 'true'));

			return;
		}

		if (!$res = $cat->withdrawal($data['amount']))
		{
			$this->_ajax_responder->sendError(array('Сумма небыла снята'));
			return ;
		}

		$this->_ajax_responder->sendResponse(array('current_amount' => $res));
	}
}