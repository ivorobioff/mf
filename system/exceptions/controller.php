<?php
namespace System\Exceptions;

class Controller extends \System\Exceptions\Exception
{

	public function stop()
	{
		die($this->getMessage());
	}
}