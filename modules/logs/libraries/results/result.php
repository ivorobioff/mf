<?php
namespace Lib\Logs\Results;

use \Plugins\Utils\SmartArray;
use \Lib\Logs\Results\Row;

class Result extends SmartArray
{
	public function offsetGet($offset)
	{
		return new Row($this->_data[$offset]);
	}

	public function toArray()
	{
		$res = array();

		foreach ($this as $value)
		{
			$res[] = $value->toArray();
		}

		return $res;
	}
}