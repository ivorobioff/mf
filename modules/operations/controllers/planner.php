<?php
namespace Controller\Operations;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use Plugins\Validator\Validator;
use Plugins\Validator\Rules\Emptiness;
use System\Lib\Http;
use \Plugins\Utils\Massive;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{
		$model_groups = new ModelGroups();
		$model_categories = new ModelCategories();

		$this->_view->groups = json_encode($model_groups->getAll());
		$this->_view->categories = json_encode($model_categories->getAll());

		$this->_view->render('operations/planner/planner.phtml');
	}

	public function readGroup()
	{
		$this->_mustBeAjax();

		$groups = new ModelGroups();

		$this->_ajax_responder->sendResponse($groups->getAll());

		return ;
	}

	public function createCategory()
	{
		$this->_mustBeAjax();

		$data = Http::post();

		$massive_rules = array(
			'title' =>  function($value) { return trim($value); },
			'amount' => function ($value) { return sprintf('%0.2f', floatval($value)); },
		);

		Massive::applyRules($data, $massive_rules);

		$validator = new Validator();

		$validator_rules = array(
			'title' => new Emptiness('Поле "Название" не должно быть пустым.')
		);

		if (!$validator->isValid($data, $validator_rules))
		{
			$this->_ajax_responder
				->sendError($validator->fetchErrors());
			return ;
		}

		$cats = new ModelCategories();

		if (!$id = $cats->addCategory($data))
		{
			$this->_ajax_responder
				->sendError(array('Unknown error'));
			return ;
		}

		$this->_ajax_responder->sendResponse(array_merge(array('id' => $id), $data));
	}

	public function updateAmount()
	{
		$this->_mustBeAjax();

		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$cats = new ModelCategories(Http::get('cat_id'));

		if (!$cats->categoryExists(Http::get('cat_id')))
		{
			//TODO Придумать обработку случая
			die("Нет такой категории");
		}

		$diff = $cats->compare(Http::get('amount'));

		$cats->setAmount(Http::get('amount'));

		$listeners->notify($diff, Http::get('cat_id'));
	}
}