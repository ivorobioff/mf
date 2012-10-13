<?php
namespace System\Error404;
/**
 * Котроллер для обработки ошибки 404 для ajax
 */

class Ajax extends \System\Mvc\Ajax\Controller
{
	public function handler()
	{
		echo 'Not found ajax page';
	}
}