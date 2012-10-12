<?php
namespace Lib\Operations;

use \Lib\Operations\Abstractions\AmountChange;
use \Model\Operations\Categories as ModelCategories;

class ManipulatorListener implements AmountChange
{
	public function onAmountChange($amount_diff, $cat_id)
	{
		$cats = new ModelCategories($cat_id);
		$cats->setCurrentAmount($amount_diff);

	}
}