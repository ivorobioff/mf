<?php
namespace System\Lib;
/**
 * Класс для работы с http функционалом.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Http
{
	static public function get($key = null, $default = false)
	{
		return  self::_request('get', $key, $default);
	}

	static public function post($key = null, $default = false)
	{
		return self::_request('post', $key, $default);
	}

	static private function _request($type, $key, $default)
	{
		$req = $type == 'post' ? $_POST : $_GET;

		if (is_null($key))
		{
			return $req;
		}

		return always_set($req, $key, $default);
	}


	static public function isAjax()
	{
		return Router::getInstance()->isAjax();
	}

	static public function redirect($url)
	{
		header('location: '.$url);
		exit(0);
	}
}