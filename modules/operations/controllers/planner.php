<?php
namespace Controller\Operations;

use Plugins\Validator\Rules\Exception;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;
use Plugins\Validator\Validator;
use Plugins\Validator\Rules\Emptiness;
use System\Lib\Http;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{
		$this->_view->render('operations/planner.phtml');
	}

	public function readGroup()
	{
		$this->_mustBeAjax();

		$groups = new ModelGroups();

		$this->_ajax_responder->sendResponse($groups->getAll());
	}

	public function createCategory()
	{
		$this->_mustBeAjax();

		$validator = new Validator();

		$validator->setRule(new Emptiness());

		if (!$validator->isValid(Http::post('title')))
		{
			$this->_ajax_responder
				->sendError($validator->fetchErrors());
				return;
		}

		$cats = new ModelCategories();

		$id = $cats->addCategory(Http::post());

		$this->_ajax_responder->sendResponse(array('id' => $id));
	}

	public function setAmount()
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