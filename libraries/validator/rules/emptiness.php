<?php
namespace Common\Lib\Validator\Rules;

use \Common\Lib\Validator\Lib\MainRule;
use \Common\Lib\Validator\Lib\Exception;

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