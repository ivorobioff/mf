<?php
namespace System\Lib;

use System\Lib\Router;

/**
 * Класс для работы с http функционалом.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Http
{
	static public function get($key = null)
	{
		return  self::_request('get', $key);
	}

	static public function post($key = null)
	{
		return self::_request('post', $key);
	}

	static private function _request($type, $key)
	{
		$req = $type == 'post' ? $_POST : $_GET;

		if (is_null($key))
		{
			return $req;
		}

		return $req[$key];
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
		$url = Router::getInstance()->getArrayPath();

		unset($url[0], $url[1], $url[2]);

		$url = array_merge(array(), $url);

		return always_set($url, $index, null);
	}

	/**
	 * Путь текущего местоположения.
	 * Данный путь включает 3 сегмента:
	 * <ul>
	 * 		<li>Модуль</li>
	 * 		<li>Контроллер</li>
	 * 		<li>Действие</li>
	 * </ul>
	 */
	static public function location()
	{
		$url = Router::getInstance()->getArrayPath();

		return ('/'.$url[0].'/'.$url[1].'/'.$url[2].'/');
	}
}