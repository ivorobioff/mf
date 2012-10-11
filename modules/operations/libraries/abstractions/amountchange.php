<?php
namespace Lib\Operations\Abstractions;

interface AmountChange
{
	public function onAmountChange($amount, $cat_id);
}