<?php
namespace Lib\Operations\Abstractions;

interface AmountChange
{
	public function onAmountChange($amount_diff, $cat_id);
}