<?php
namespace Controller\Operations;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Planner as ModelPlanner;
use \System\Exceptions\Controller as ExceptionController;

class Planner extends \System\Mvc\Controller
{
	public function index()
	{

	}

	public function setAmount(array $params = array())
	{
		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$planner = new ModelPlanner($params['cat_id']);

		if (!$planner->categoryExists($params['cat_id']))
		{
			throw new ExceptionController('No such category');
		}

		$diff = $planner->compare($params['amount']);

		$planner->setAmount($params['amount']);

		$listeners->notify($diff, $params['cat_id']);
	}
}