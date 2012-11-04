<?php
namespace System\Mvc;

class RowException extends \Exception
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message = null, $code = null, $previous = null);
	}
}