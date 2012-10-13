<?php
namespace System\Error404;

class Controller extends \System\Mvc\Controller
{
	public function handler()
	{
		echo 'Page not found';
	}

	public function ajaxHandler()
	{
		echo 'Ajax request not found';
	}
}