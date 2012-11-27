<?php
namespace Lib\Archive;

use Plugins\Utils\SmartArray;

class Row extends SmartArray
{
	private $_to_unserialize = array('budget', 'categories', 'logs');

	public function offsetGet($offset)
	{
		if (in_array($offset, $this->_to_unserialize))
		{
			return unserialize($this->_data[$offset]);
		}

		return $this->_data[$offset];
	}
}