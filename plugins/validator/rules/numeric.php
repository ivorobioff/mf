<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class Numeric extends MainRule
{
	public function validate($param = '')
	{
		if (!is_numeric($param))
		{
			throw new Exception($this->_message);
		}
	}
}