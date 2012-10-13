<?php
namespace Controller\Operations;

use Common\Lib\Validator\Rules\Exception;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Common\Lib\Validator\Validator;
use \Common\Lib\Validator\Rules\Emptiness;
use \System\Lib\Http;
use \Common\Lib\Exceptions\Ajax\NoData;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{
		echo 'it is working';
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
			throw new NoData('There is no such a category.');
		}

		$diff = $cats->compare(Http::get('amount'));

		$cats->setAmount(Http::get('amount'));

		$listeners->notify($diff, Http::get('cat_id'));
	}

	public function addCategory()
	{
		$this->_mustBeAjax();

		$validator = new Validator();

		$validator->setRule(new Emptiness());

		if (!$validator->isValid(Http::post('title')))
		{
			$this->_ajax_responder
				->attachData($validator->fetchErrors())
				->sendError();

			return;
		}

		$cats = new ModelCategories();
		$cats->addCategory($posts);
	}
}