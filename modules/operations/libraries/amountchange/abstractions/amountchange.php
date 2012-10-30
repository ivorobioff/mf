<?php
namespace Lib\Operations\AmountChange\Abstractions;

abstract class AmountChange
{
	abstract public function onAmountChange($amount_diff, $cat_id);

	public function test($amount_diff, $cat_id)
	{

	}
}