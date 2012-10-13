<?php
namespace System\Error404;
use \System\Exceptions\Controller as ExceptionController;
use \System\Lib\Http;
/**
 * Класс исключения для обработки ошибки 404
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Exception extends ExceptionController
{
	protected function _handler()
	{
		$controller  = new Controller();

		if (Http::isAjax())
		{
			$controller->ajaxHandler();

			return;
		}

		$controller->handler();
	}
}