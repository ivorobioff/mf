<?php
namespace Lib\Common;

class FrontErrors extends \Exception
{
	private $_message = array();

	public function __construct($message)
	{
		parent::__construct();

		$this->_message = $message;
	}

	public function get()
	{
		return array($this->_message);
	}
}