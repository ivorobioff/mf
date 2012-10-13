<?php
namespace System\Lib;
/**
 * Класс для работы с http функционалом.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Http
{
	static public function get($key, $default = false)
	{
		return always_set($_GET, $key, $default);
	}

	static public function post($key, $default = false)
	{
		return always_set($_POST, $key, $default);
	}

	static public function isAjax()
	{
		Router::getInstance()->isAjax();
	}
}