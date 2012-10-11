<?php
namespace Lib\Operations;

use \Lib\Operations\Abstractions\AmountChange;

class AmountChangeListeners
{
	private $_listeners = array();

	public function addListener(AmountChange $listener)
	{
		$this->_listeners[] = $listener;
	}

	public function notify($amount, $cat_id)
	{
		foreach ($this->_listeners as $listener)
		{
			$listener->onAmountChange($amount, $cat_id);
		}
	}
}