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
use \Lib\Logs\Logger\Logger;

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

		if (!$cat->exists())
		{
			return $this->_sendError(array('Категория с id '.$data['id'].' не найдена.'));
		}

		$diff_amount = $cat->getCurrentAmount() - $data['amount'];

		if ($diff_amount < 0)
		{
			return $this->_sendError(array('requested_amount' => $diff_amount * (-1)));
		}

		$logger = new Logger($cat, Logger::AC_CATEGORY_WITHDRAWAL);

		$logger->fixBefore();

		if (!$cat->withdrawal($data['amount']))
		{
			return $this->_sendError(array('Сумма небыла снята'));
		}

		$budget = ModelBudget::getInstance();
		$budget->addRealExpenses($data['amount']);

		$logger->fixAfter();
		$logger->finalize($data['amount'], $data['comment']);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => $cat->getCurrentAmount()),
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

		$logger = new Logger($cat, Logger::AC_REQUEST_AMOUNT);
		$logger->fixBefore();

		$cat->requestAmount($data['requested_amount']);

		$budget = ModelBudget::getInstance();

		$expense = $cat_info['current_amount'] + $data['requested_amount'];
		$budget->addRealExpenses($expense);

		$logger->fixAfter();
		$current_fixation = $logger->getFixation();
		$logger->finalize($data['requested_amount']);

		$logger = new Logger($cat, Logger::AC_CATEGORY_WITHDRAWAL);
		$logger->setFixation($current_fixation);
		$logger->finalize($expense, $data['comment']);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => '0.00'),
			'budget' => $budget->getSummary()
		));
	}

	public function returnAmount()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		$cat_info = $cat->get();

		if (!$cat_info)
		{
			return $this->_sendError(array('Категория с id '.Http::post('id').' не найдена.'));
		}

		$logger = new Logger($cat, Logger::AC_RETURN_AMOUNT);
		$logger->fixBefore();

		$cat->returnAmount();

		$logger->fixAfter();
		$current_fixation = $logger->getFixation();
		$logger->finalize($cat_info['current_amount']);

		$logger = new Logger($cat, Logger::AC_CHANGE_AMOUNT);
		$logger->setFixation($current_fixation);
		$logger->finalize($cat_info['current_amount'] * -1);

		$this->_sendExtendedResponse(array(
			'def' => array('current_amount' => '0.00'),
			'budget' => ModelBudget::getInstance()->getSummary()
		));
	}
}