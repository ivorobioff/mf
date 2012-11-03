<?php
namespace Controller\Operations;

use \Facade\Operations\Planner as FacadePlanner;
use \Model\Operations\Categories as ModelCategories;
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

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		if (!Validator::isValid($data, Helpers\Planner::getCategoryValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$cats = new ModelCategories();

		if (!$id = $cats->add(Helpers\Planner::getNeededDataForCategory($data)))
		{
			return $this->_sendError(array('Unknown error'));
		}

		try
		{
			FacadePlanner::setAmount($id, $data['amount']);
		}
		catch (FrontErrors $ex)
		{
			return $this->_sendError($ex->get());
		}

		$cat = new ModelCategories($id);

		$this->_sendResponse($cat->get());
	}

	public function updateCategory()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		if (!Validator::isValid($data, Helpers\Planner::getCategoryValidatorRules()))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$cat = new ModelCategories($data['id']);

		$cat->edit(Helpers\Planner::getNeededDataForCategory($data));

		try
		{
			FacadePlanner::setAmount($data['id'], $data['amount']);
		}
		catch (FrontErrors $ex)
		{
			return $this->_sendError($ex->get());
		}

		$this->_sendResponse($cat->get());
	}

	public function deleteCategory()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		if (!$cat->delete())
		{
			return $this->_sendError(array('Категория не удалена. ID '.Http::post('id')));
		}
	}
}