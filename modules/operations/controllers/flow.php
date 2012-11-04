<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Numeric;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use \Model\Operations\Budget as ModelBudget;
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

		$validator_rules = array('amount' => new Numeric('Сумма должна быть числовым значением'));

		if (!Validator::isValid($data, $validator_rules))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		Massive::applyRules($data, array('amount' => function($value){
			return sprintf('%0.2f', floatval($value));
		} ));

		$cat = new ModelCategories($data['id']);

		$diff_amount = $cat->getCurrentAmount() - $data['amount'];

		if ($diff_amount < 0)
		{
			return $this->_sendError(array('requested_amount' => $diff_amount * (-1)));
		}

		if (!$cat->withdrawal($data['amount']))
		{
			return $this->_sendError(array('Сумма небыла снята'));
		}

		$budget = new ModelBudget(1);
		$budget->withdrawal($data['amount']);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => $cat->getCurrentAmount()),
			'budget' => $budget->getStatistics()
		));
	}

	public function requestAmount()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));


		$this->_sendResponse(array('current_amount' => '0.00'));
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
			return $this->_sendError($ex->get());
		}

		$this->_sendResponse(array('current_amount' => '0.00'));
	}
}