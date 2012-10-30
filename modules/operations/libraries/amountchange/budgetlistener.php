<?php
namespace Lib\Operations\AmountChange;

use \Lib\Operations\AmountChange\Abstractions\AmountChange;
use \Model\Operations\AmountChange\Budget as ModelBudget;

class BudgetListener extends AmountChange
{
	public function onAmountChange($amount_diff, $cat_id)
	{

	}
}