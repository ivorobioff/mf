<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class Emptiness extends MainRule
{
	public function validate($param = '')
	{
		if (empty($param))
		{
			throw new Exception($this->_message);
		}
	}
}