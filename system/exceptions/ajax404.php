<?php
namespace System\Exceptions;
use System\Exceptions\Exception;
/**
 * Класс исключения. Обрабатывает ошибку 404.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Ajax404 extends Error404
{
	protected function _handler(array $gets, array $posts)
	{
		$controller  = new \System\Error404\Ajax();
		$controller->handler();
	}
}