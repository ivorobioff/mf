<?php
namespace Controller\Operations;

use \Facade\Operations\Planner as FacadePlanner;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Groups as ModelGroups;
use \Plugins\Validator\Validator;
use \System\Lib\Http;
use \Plugins\Utils\Massive;
use \Lib\Common\FrontErrors;
use \Controller\Common\Layout;

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

		Massive::applyRules($data, Helpers\Planner::getGroupMassiveRules());

		if (!Validator::isValid($data, Helpers\Planner::getGroupValidatorRules()))
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

		Massive::applyRules($data, Helpers\Planner::getGroupMassiveRules());

		if (!Validator::isValid($data, Helpers\Planner::getGroupValidatorRules()))
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

		if (!Validator::isValid(Http::post(), Helpers\Planner::getCategoryValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		$cats = new ModelCategories();

		$data['current_amount'] = $data['amount'];

		if (!$data['id'] = $cats->add($data))
		{
			return $this->_sendError(array('Unknown error'));
		}

		$this->_sendExtendedResponse(array(
			'def' => $data,
			'budget' => ModelBudget::getInstance()->getSummary()
		));
	}

	public function updateCategory()
	{
		$this->_mustBeAjax();

		if (!Validator::isValid(Http::post(), Helpers\Planner::getCategoryValidatorRulesWithId()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		$cat = new ModelCategories($data['id']);

		if ($cat->exists() === false)
		{
			return $this->_sendError(array('Категории с id '.$data['id'].' не существует'));
		}

		$new_current_amount = Helpers\Planner::getNewCurrentAmount($data);

		if (($new_current_amount) < 0)
		{
			return $this->_sendError(array('Текущая сумма не может быть меньше нуля'));
		}

		$data['current_amount'] = $new_current_amount;

		$cat->edit($data);

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

		if (!$cat->delete())
		{
			return $this->_sendError(array('Категория не удалена. ID '.Http::post('id')));
		}

		return $this->_sendResponse(ModelBudget::getInstance()->getSummary());
	}
}