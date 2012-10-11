<?php
namespace Lib\Operations;

use \Lib\Operations\Abstractions\AmountChange;

class BudgetListener implements AmountChange
{
	public function onAmountChange($amount_diff, $cat_id)
	{
		echo 'Budget Reaction';
	}
}