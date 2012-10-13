<?php
namespace Controller\Operations;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Common\Lib\Validator\Validator;
use \Common\Lib\Validator\Rules\Emptiness;
use \System\Ajax\Templates as AjaxTemplates;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{
		echo 'it is working';
	}

	public function setAmount()
	{
		$this->_validateAjax();

		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$cats = new ModelCategories(Http::get('cat_id'));

		if (!$cats->categoryExists(Http::get('cat_id')))
		{

		}

		$diff = $cats->compare(Http::get('amount'));

		$cats->setAmount(Http::get('amount'));

		$listeners->notify($diff, Http::get('cat_id'));
	}

	public function addCategory()
	{
		$this->_validateAjax();

		$validator = new Validator();

		$validator->setRule(new Emptiness());

		if (!$validator->isValid(Http::post('title')))
		{
			$ajax = new AjaxResponder();
			$ajax->attachData($validator->fetchErrors())->sendError();

			return;
		}

		$cats = new ModelCategories();
		$cats->addCategory($posts);
	}
}