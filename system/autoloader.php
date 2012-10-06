<?php
/**
 * Класс для autoloading.
 * В зависимости от типа класса выберается путь как файлу с классом.
 * Каждый тип класс имеет соответствующий метод для получения пути.
 * @author Igor
 */
class Autoloader
{
	private $_class_type;

	private $_class_array;

	static private $_instance;

	static public function getInstance()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getPath($class)
	{
		$this->_class_array = explode('\\', $class);

		$this->_class_type = strtolower($this->_class_array[0]);

		$method = '_'.$this->_class_type;

		if (!method_exists($this, $method))
		{
			return false;
		}

		return $this->$method();
	}

	private function _db()
	{

	}

	private function _mvc()
	{
		$array_class = $this->_class_array;

		unset($array_class[0]);

		return '/system/mvc/'.implode('/', $array_class).'.php';
	}

	private function _controller()
	{
		return $this->_module('controllers');
	}

	private function _model()
	{
		return $this->_module('models');
	}

	private function _lib()
	{
		return $this->_module('libraries');
	}

	private function _module($element)
	{
		$array_class = $this->_class_array;

		$array_class = explode('\\', strtolower(implode('\\', $array_class)));

		end($array_class);
		$array_class[key($array_class)] = ucfirst(current($array_class));

		$module_name = $array_class[1];
		unset($array_class[0], $array_class[1]);

		return '/modules/'.$module_name.'/'.$element.'/'.implode('/', $array_class).'.php';
	}
}

function __autoload($class)
{
	include_once Autoloader::getInstance()->getPath($class);
}
