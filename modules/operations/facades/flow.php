<?php
namespace Facade\Operations;

use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Categories as ModelBudget;

class Flow
{
	static public function withdrawal($id, $amount)
	{
		$budget = new ModelBudget(1);
		$cat = new ModelCategories($id);
	}
}