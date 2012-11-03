<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class Int extends MainRule
{
	public function validate($param = '')
	{
		if (!is_int($param))
		{
			throw new Exception($this->_message);
		}
	}
}