<?php
function pre($str, $die = false)
{
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function pred($str, $die = false)
{
	pre($str);
	die();
}

function always_set($array, $key, $default = false)
{
	return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Проверяет если заданный путь является текущем местоположением.
 */
function is_location($path)
{
	return trim(\System\Lib\Http::location(), '/') == trim($path, '/');
}