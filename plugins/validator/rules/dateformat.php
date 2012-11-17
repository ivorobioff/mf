<?php
namespace Plugins\Validator\Rules;

use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception;

class DateFormat extends MainRule
{
	private $_format;

	public function __construct($format, $message = '')
	{
		parent::__construct($message);

		$this->_format = $format;
	}

	public function validate($param = '')
	{
		$date = new \DateTime();

		$formated_date = $date->createFromFormat($this->_format, $param);

		if (!$formated_date || $formated_date->format($this->_format) != $param)
		{
			throw new Exception($this->_message);
		}
	}
}