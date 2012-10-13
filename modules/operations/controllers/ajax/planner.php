<?php
namespace Controller\Operations\Ajax;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;
use \Controller\Operations\Exceptions\WrongCategory;
use \Common\Lib\Validator\Validator;
use \Common\Lib\Validator\Rules\Emptiness;

class Planner extends \System\Mvc\Ajax\Controller
{
	public function setAmount(array $gets, array $posts)
	{
		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$cats = new ModelCategories($gets['cat_id']);

		if (!$cats->categoryExists($gets['cat_id']))
		{
			throw new WrongCategory($gets, $posts);
		}

		$diff = $cats->compare($gets['amount']);

		$cats->setAmount($gets['amount']);

		$listeners->notify($diff, $gets['cat_id']);
	}

	public function addCategory(array $gets, array $posts)
	{
		$validator = new Validator();

		$validator->setRule(Emptiness());

		if (!$validator->isValid($posts['title']))
		{
			echo json_encode($validator->fetchErrors());

			return;
		}

		$cats = new ModelCategories();
		$cats->addCategory($posts);
	}
}