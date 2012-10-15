<?php
namespace System\Lib;
/**
 * Класс является обверткой $_SERVER;
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Server
{
	static function get($key)
	{
		return always_set($_SERVER, strtoupper($key));
	}
}