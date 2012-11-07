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
use \Facade\Log\Log as FacadeLog;

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

		$cat_info = $cat->get();

		if (!$cat_info)
		{
			return $this->_sendError(array('Категория с id '.$data['id'].' не найдена.'));
		}

		$diff_amount = $cat_info['current_amount'] - $data['amount'];

		if ($diff_amount < 0)
		{
			return $this->_sendError(array('requested_amount' => $diff_amount * (-1)));
		}

		if (!$cat->withdrawal($data['amount']))
		{
			return $this->_sendError(array('Сумма небыла снята'));
		}

		$cat_info = $cat->get();

		FacadeLog::logIt(array(
			'item_name' => $cat_info['title'],
			'amount' => $data['amount'],
			'app_comment' => FacadeLog::AC_CATEGORY_WITHDRAWAL,
			'user_comment' => $data['comment'],
		));

		$budget = new ModelBudget(1);
		$budget->addRealExpenses($data['amount']);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => $cat_info['current_amount']),
			'budget' => $budget->getSummary()
		));
	}

	public function requestAmount()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$cat = new ModelCategories($data['id']);

		$cat_info = $cat->get();

		if (!$cat_info)
		{
			return $this->_sendError(array('Категория с id '.$data['id'].' не найдена.'));
		}

		$cat->requestAmount($data['requested_amount']);

		FacadeLog::logIt(array(
			'item_name' => $cat_info['title'],
			'amount' =>  $data['requested_amount'],
			'app_comment' => FacadeLog::AC_REQUEST_AMOUNT
		));

		FacadeLog::logIt(array(
			'item_name' => $cat_info['title'],
			'amount' =>  $data['requested_amount'] + $cat_info['current_amount'],
			'app_comment' => FacadeLog::AC_CATEGORY_WITHDRAWAL,
			'user_comment' => $data['comment']
		));

		$budget = new ModelBudget(1);

		$budget->addRealExpenses($cat_info['current_amount'] + $data['requested_amount']);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => '0.00'),
			'budget' => $budget->getSummary()
		));
	}

	public function returnAmount()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		$cat->returnAmount();

		$budget = new ModelBudget(1);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => '0.00'),
			'budget' => $budget->getSummary()
		));
	}
}