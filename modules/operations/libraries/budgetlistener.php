<?php
namespace Lib\Operations;

use \Lib\Operations\Abstractions\AmountChange;
use \Model\Operations\Budget as ModelBudget;

class BudgetListener implements AmountChange
{
	public function onAmountChange($amount_diff, $cat_id)
	{
		$budget = new ModelBudget();
		$budget->addAmount($amount_diff * (-1));
	}
}