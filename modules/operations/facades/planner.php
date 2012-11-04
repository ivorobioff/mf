<?php
namespace Facade\Operations;

use \Lib\Operations\AmountChange\AmountChangeListeners;
use \Lib\Operations\AmountChange\BudgetListener;
use \Lib\Operations\AmountChange\FlowListener;
use \Model\Operations\Categories as ModelCategories;

class Planner
{
	static public function setAmount($id, $amount)
	{
		$listeners = new AmountChangeListeners();

		$listeners->addListener(new FlowListener());

		$cat = new ModelCategories($id);

		$diff = $cat->getDiff($amount);

		$listeners->test($diff, $id);

		$cat->setAmount($amount);

		$listeners->notify($diff, $id);
	}
}