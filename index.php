<?php
header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();

function root_path()
{
	return $_SERVER['DOCUMENT_ROOT'];
}

include_once '/system/lib/shortcuts.php';
include_once '/system/lib/autoloader.php';


function __autoload($class)
{
	include_once Autoloader::getInstance()->getPath($class);
}

try
{
	\System\Lib\Router::getInstance()->run();
}
catch (\System\Error404\Exception $ex)
{
	$ex->load();
}