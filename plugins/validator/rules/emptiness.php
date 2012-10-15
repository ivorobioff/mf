<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class Emptiness implements MainRule
{
	public function validate($param = '')
	{
		if (!trim($param))
		{
			throw new Exception('The field cannot be empty');
		}
	}
}