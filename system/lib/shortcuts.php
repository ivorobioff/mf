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

/**
 * Проверяет чтоб в массиве $data были заданны все поля из списка $fields
 * @param array $data
 * @param array $fields
 */
function is_all_set(array $data = array(), array $fields = array())
{
	foreach ($fields as $name)
	{
		if (!isset($data[$name]))
		{
			return false;
		}
	}

	return true;
}