<?php
namespace System\Exceptions;
use System\Exceptions\Exception;

class Error404 extends Exception
{
	public function loadPage()
	{
		include_once '/system/404.php';
	}
}