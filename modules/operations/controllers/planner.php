<?php
namespace Controller\Operations;

use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Groups as ModelGroups;
use \Plugins\Validator\Validator;
use \System\Lib\Http;
use \Plugins\Utils\Massive;
use \Lib\Common\FrontErrors;
use \Controller\Common\Layout;
use \Lib\Log\Logger\Logger;
use \Controller\Operations\Helpers\Planner as HelperPlanner;

class Planner extends Layout
{
	public function index()
	{
		$model_groups = new ModelGroups();
		$model_categories = new ModelCategories();

		$this->_view->groups = json_encode($model_groups->getAll());
		$this->_view->categories = json_encode($model_categories->getAll());

		$this->_view->render('operations/planner/index.phtml');
	}

	public function createGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, HelperPlanner::getGroupMassiveRules());

		if (!Validator::isValid($data, HelperPlanner::getGroupValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$groups = new ModelGroups();

		if (!$id = $groups->add($data))
		{
			return $this->_sendError(array('Unknown error'));
		}

		$this->_sendResponse(array_merge(array('id' => $id), $data));
	}

	public function updateGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, HelperPlanner::getGroupMassiveRules());

		if (!Validator::isValid($data, HelperPlanner::getGroupValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$group = new ModelGroups($data['id']);

		$group->edit($data);

		$this->_sendResponse($data);
	}


	public function deleteGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$group = new ModelGroups($data['id']);

		if (!$group->isEmpty())
		{
			return $this->_sendError(array('Группа должна быть пустой'));
		}

		if (!$group->delete())
		{
			$this->_sendError(array('Группа небыла удаленна'));
		}
	}

	public function createCategory()
	{
		$this->_mustBeAjax();

		if (!Validator::isValid(Http::post(), HelperPlanner::getCategoryValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$data = Http::post();

		Massive::applyRules($data, HelperPlanner::getCategoryMassiveRules());

		$logger = new Logger(null, Logger::AC_CREATE_CATEGORY);
		$logger->fixBefore();

		$cats = new ModelCategories();

		$data['current_amount'] = $data['amount'];

		if (!$data['id'] = $cats->add($data))
		{
			return $this->_sendError(array('Unknown error'));
		}

		$cat = new ModelCategories($data['id']);
		$cat_info = $cat->get();

		$logger->setModel($cat);
		$logger->fixAfter()->finalize($cat_info['amount']);

		$this->_sendExtendedResponse(array(
			'def' => $cat_info,
			'budget' => ModelBudget::getInstance()->getSummary()
		));
	}

	public function updateCategory()
	{
		$this->_mustBeAjax();

		if (!Validator::isValid(Http::post(), HelperPlanner::getCategoryValidatorRulesWithId()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$data = Http::post();

		Massive::applyRules($data, HelperPlanner::getCategoryMassiveRules());

		$cat = new ModelCategories($data['id']);

		$cat_info = $cat->get();

		if (!$cat_info)
		{
			return $this->_sendError(array('Категории с id '.$data['id'].' не существует'));
		}

		$new_current_amount = HelperPlanner::getNewCurrentAmount($cat_info, $data);

		if (($new_current_amount) < 0)
		{
			return $this->_sendError(array('Текущая сумма не может быть меньше нуля'));
		}

		$amount_diff = HelperPlanner::getAmountDiff($cat_info, $data);

		$logger = new Logger($cat, Logger::AC_CHANGE_AMOUNT);
		$logger->fixBefore();

		$data['current_amount'] = $new_current_amount;

		$cat->edit($data);

		$logger->fixAfter();

		if (HelperPlanner::canLog($amount_diff))
		{
			$logger->finalize($amount_diff);
		}

		$this->_sendExtendedResponse(array(
			'def' => $cat->get(),
			'budget' => ModelBudget::getInstance()->getSummary()
		));
	}

	public function deleteCategory()
	{
		$this->_mustBeAjax();

		if (!Validator::isValid(Http::post(), array('id' => true)))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$cat = new ModelCategories(Http::post('id'));

		if (!$cat->canDelete())
		{
			return $this->_sendError(array('Категория не может быть удалена. Она уже задействована в операциях.'));
		}

		$logger = new Logger($cat, Logger::AC_DELETE_CATEGORY);
		$logger->fixBefore();

		$was_amount = $cat->getAmount();

		if (!$cat->delete())
		{
			return $this->_sendError(array('Категория не удалена. ID '.Http::post('id')));
		}

		$logger->setModel(null);
		$logger->fixAfter()->finalize($was_amount);

		return $this->_sendResponse(ModelBudget::getInstance()->getSummary());
	}
}