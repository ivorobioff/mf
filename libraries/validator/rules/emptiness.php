<?php
namespace Common\Lib\Validator\Rules;

class Emptiness implements MainRule
{
	public function validate($param = '')
	{
		if (!trim($param))
		{
			throw new Exception('Поле не может быть пустым');
		}
	}
}