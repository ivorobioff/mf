<?php
namespace System\Exceptions;

class Exception extends \Exception
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function log()
	{
		echo $this->getMessage();
	}
}