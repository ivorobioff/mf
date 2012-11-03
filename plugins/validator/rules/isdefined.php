<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class IsDefined extends MainRule
{
	public function __construct()
	{
		parent::__construct('Uknown field');
	}

	public function validate($value = null, $field = null, array $data = array())
	{
		if (!isset($data[$field]))
		{
			throw new Exception($this->_message);
		}
	}
}