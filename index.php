<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();

define('DEFAULT_PATH', 'manipulator/index/index');

function root_path()
{
	return $_SERVER['DOCUMENT_ROOT'];
}

include_once '/system/shortcuts.php';
include_once '/system/autoloader.php';

$path = $_SERVER['REQUEST_URI'];

$path = explode('?', $path);

$query = always_set($path, 1, false);

$path = $path[0];

if ($query)
{
	parse_str($query, $_GET);
}

$path = trim(trim($path), '/');

if ($path == '')
{
	$path = DEFAULT_PATH;
}

$array_path = explode('/', $path);

$array_path['module'] = $array_path[0];
$array_path['controller'] = always_set($array_path, 1, 'index');
$array_path['action'] = always_set($array_path, 2, 'index');

$controller_file = root_path().'/modules/'.$array_path['module'].'/controllers/'.$array_path['controller'].'.php';

if (!file_exists($controller_file))
{
	include_once '/system/404.php';
	exit();
}

$controller_class = 'Controller\\'.$array_path['module'].'\\'.ucfirst($array_path['controller']);

$controller = new $controller_class();

if (!method_exists($controller, $array_path['action']))
{
	include_once '/system/404.php';
	exit();
}

$controller->$array_path['action']();