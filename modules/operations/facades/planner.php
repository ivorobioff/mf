<?php
namespace Facade\Operations;

use \Lib\Operations\AmountChange\AmountChangeListeners;
use \Lib\Operations\AmountChange\BudgetListener;
use \Lib\Operations\AmountChange\FlowListener;
use \Model\Operations\Categories as ModelCategories;

class Planner
{
	static function setAmount($id, $amount)
	{
		$listeners = new AmountChangeListeners();

		$listeners->addListener(new BudgetListener());
		$listeners->addListener(new FlowListener());

		$cat = new ModelCategories($id);

		$diff = $cat->getDiff($amount);

		$cat->setAmount($amount);

		$listeners->notify($diff, $id);
	}
}