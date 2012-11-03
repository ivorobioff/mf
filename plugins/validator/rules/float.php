<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class Float extends MainRule
{
	public function validate($param = '')
	{
		if (!is_float($param))
		{
			throw new Exception($this->_message);
		}
	}
}