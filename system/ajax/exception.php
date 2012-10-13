<?php
namespace System\Ajax;

use \System\Exceptions\Controller as ExceptionController;
/**
 * Класс исключения.
 * Используется для обработки ситуации, когда методы контроллеров предназначенные для ajax запросов
 * напрямую вызываются GET-ом или POST-ом.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Exception extends ExceptionController
{
	/**
	 * Обработчик который передает управление System\Ajax\Noajax() контроллеру для обработки исключения.
	 * @see System\Exceptions\Controller::_handler()
	 */
	protected function _handler()
	{
		$controller = new Noajax();
		$controller->handler();
	}
}