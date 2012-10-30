<?php
namespace Lib\Operations\AmountChange;

use \Lib\Operations\AmountChange\Abstractions\AmountChange;

class AmountChangeListeners
{
	private $_listeners = array();

	public function addListener(AmountChange $listener)
	{
		$this->_listeners[] = $listener;
	}

	public function notify($amount_diff, $cat_id)
	{
		foreach ($this->_listeners as $listener)
		{
			$listener->test($amount_diff, $cat_id);
		}

		foreach ($this->_listeners as $listener)
		{
			$listener->onAmountChange($amount_diff, $cat_id);
		}
	}
}