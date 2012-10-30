<?php
namespace Facade\Operations;

use \Lib\Operations\AmountChangeListeners;
use \Lib\Operations\BudgetListener;
use \Lib\Operations\ManipulatorListener;
use \Model\Operations\Categories as ModelCategories;

class Planner
{
	static function setAmountToCategory($id, $amount)
	{
		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new ManipulatorListener());

		$cats = new ModelCategories($id);

		$diff = $cats->getDiff($amount);

		$cats->setAmount($amount);

		$listeners->notify($diff, $id);
	}
}