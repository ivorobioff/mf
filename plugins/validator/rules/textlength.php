<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class TextLength extends MainRule
{
	private $_min;
	private $_max;

	public function __construct($min, $max = null, $message = '')
	{
		parent::__construct($message);

		$this->_max = $min;
		$this->_max = $max;
	}

	public function validate($param = '')
	{
		$len = strlen($param);

		$allow = true;

		if ($len < $this->_min)
		{
			$allow = false;
		}

		if (!is_null($this->_max) && $len > $max)
		{
			$allow = false;
		}

		if (!$allow)
		{
			throw new Exception($this->_message);
		}
	}
}