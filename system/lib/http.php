<?php
namespace System\Lib;

use System\Lib\Router;


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
		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

	static public function redirect($url)
	{
		header('location: '.$url);
		exit(0);
	}

	static public function params($index = null)
	{
		$url = Router::getInstance()->getRequestParams();

		if (is_null($index))
		{
			return $url;
		}

		return $url[$index];
	}
}