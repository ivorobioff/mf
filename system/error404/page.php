<?php
namespace System\Error404;
/**
 * Котроллер для обработки ошибки 404
 */
class Page extends \System\Mvc\Controller
{
	public function handler()
	{
		echo 'Not found page';
	}
}