<?php
namespace System\Exceptions;
use System\Exceptions\Exception;
/**
 * Класс исключения. Обрабатывает ошибку 404.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Error404 extends Exception
{
	public function loadPage()
	{
		include_once '/system/404.php';
	}
}