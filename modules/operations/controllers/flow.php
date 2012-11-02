<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Emptiness;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use \System\Lib\Http;
use \Plugins\Utils\Massive;
use \Plugins\Validator\Validator;
use \Facade\Operations\Planner as FacadePlanner;
use \Controller\Common\Layout;
use \Lib\Common\FrontErrors;

class Flow extends Layout
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

		$diff_amount = $cat->getCurrentAmount() -  $data['amount'];

		if ($diff_amount < 0)
		{
			$this->_ajax_responder->sendError(array('requested_amount' => $diff_amount * (-1)));

			return;
		}

		if (!$cat->withdrawal($data['amount']))
		{
			$this->_ajax_responder->sendError(array('Сумма небыла снята'));
			return ;
		}

		$this->_ajax_responder->sendResponse(array('current_amount' => $cat->getCurrentAmount()));
	}

	public function requestAmount()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		try
		{
			FacadePlanner::setAmount(Http::post('id'), $cat->getAmount() + Http::post('requested_amount'));
		}
		catch (FrontErrors $ex)
		{
			$this->_ajax_responder->sendError($ex->get());
			return ;
		}

		$cat->setCurrentAmount(0);

		$this->_ajax_responder->sendResponse(array('current_amount' => '0.00'));
	}

	public function returnAmount()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		try
		{
			FacadePlanner::setAmount(Http::post('id'), $cat->getAmount() - $cat->getCurrentAmount());
		}
		catch (FrontErrors $ex)
		{
			$this->_ajax_responder->sendError($ex->get());
			return ;
		}

		$this->_ajax_responder->sendResponse(array('current_amount' => '0.00'));
	}
}