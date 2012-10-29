<?php
namespace Controller\Operations;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use \Plugins\Validator\Validator;
use \System\Lib\Http;
use \Plugins\Utils\Massive;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{
		$model_groups = new ModelGroups();
		$model_categories = new ModelCategories();

		$this->_view->groups = json_encode($model_groups->getAll());
		$this->_view->categories = json_encode($model_categories->getAll());

		$this->_view->render('operations/planner/index.phtml');
	}

	public function readGroup()
	{
		$this->_mustBeAjax();

		$groups = new ModelGroups();

		$this->_ajax_responder->sendResponse($groups->getAll());

		return ;
	}

	public function createGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getGroupMassiveRules());

		$validator = new Validator();

		if (!$validator->isValid($data, Helpers\Planner::getGroupValidatorRules()))
		{
			$this->_ajax_responder
				->sendError($validator->fetchErrors());
			return ;
		}

		$groups = new ModelGroups();

		if (!$id = $groups->add($data))
		{
			$this->_ajax_responder
				->sendError(array('Unknown error'));
			return ;
		}

		$this->_ajax_responder->sendResponse(array_merge(array('id' => $id), $data));
	}

	public function updateGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getGroupMassiveRules());

		$validator = new Validator();

		if (!$validator->isValid($data, Helpers\Planner::getGroupValidatorRules()))
		{
			$this->_ajax_responder
			->sendError($validator->fetchErrors());
			return ;
		}

		$group = new ModelGroups($data['id']);

		$group->edit($data);

		$this->_ajax_responder->sendResponse($data);
	}


	public function deleteGroup()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$group = new ModelGroups($data['id']);

		if (!$group->isEmpty())
		{
			$this->_ajax_responder->sendError(array('Группа должна быть пустой'));
			return ;
		}

		if (!$group->delete())
		{
			$this->_ajax_responder->sendError(array('Группа небыла удаленна'));
			return ;
		}
	}

	public function createCategory()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		$validator = new Validator();

		if (!$validator->isValid($data, Helpers\Planner::getCategoryValidatorRules()))
		{
			$this->_ajax_responder
				->sendError($validator->fetchErrors());
			return ;
		}

		$cats = new ModelCategories();

		$data['current_amount'] = $data['amount'];

		if (!$id = $cats->add($data))
		{
			$this->_ajax_responder
				->sendError(array('Unknown error'));
			return ;
		}

		$this->_ajax_responder->sendResponse(array_merge(array('id' => $id), $data));
	}

	public function updateCategory()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		Massive::applyRules($data, Helpers\Planner::getCategoryMassiveRules());

		$validator = new Validator();

		if (!$validator->isValid($data, Helpers\Planner::getCategoryValidatorRules()))
		{
			$this->_ajax_responder
				->sendError($validator->fetchErrors());
			return ;
		}

		$cat = new ModelCategories($data['id']);

		$cat->edit($data);

		$this->_ajax_responder->sendResponse($data);
	}

	public function deleteCategory()
	{
		$this->_mustBeAjax();

		$cat = new ModelCategories(Http::post('id'));

		if (!$cat->delete())
		{
			$this->_ajax_responder->sendError(array('Категория не удалена. ID '.Http::post('id')));
			return ;
		}
	}

	public function setAmount()
	{
		$this->_mustBeAjax();

		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$cats = new ModelCategories(Http::get('cat_id'));

		$diff = $cats->getDiff(Http::get('amount'));

		$cats->setAmount(Http::get('amount'));

		$listeners->notify($diff, Http::get('cat_id'));
	}
}