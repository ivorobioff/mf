<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();

function root_path()
{
	return $_SERVER['DOCUMENT_ROOT'];
}

include_once '/system/shortcuts.php';
include_once '/system/autoloader.php';
include_once '/system/router.php';

function __autoload($class)
{
	include_once Autoloader::getInstance()->getPath($class);
}

$router = new Router($_SERVER['REQUEST_URI']);

try
{
	$router->run();
}
catch (\System\Exceptions\Error404 $ex)
{
	$ex->load();
}