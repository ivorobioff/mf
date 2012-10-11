<?php
namespace Lib\Operations;

use \Lib\Operations\Abstractions\AmountChange;

class ManipulatorListener implements AmountChange
{
	public function onAmountChange($amount_diff, $cat_id)
	{
		echo 'Manipulator Reaction';
	}
}