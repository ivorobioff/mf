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

	private $_class;

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
		$this->_class = $class;

		$class = str_replace('_', '\\', $class);

		$this->_class_array = explode('\\', strtolower($class));

		$this->_class_type = $this->_class_array[0];

		$method = '_'.$this->_class_type;

		if (!method_exists($this, $method))
		{
			return false;
		}

		return $this->$method();
	}

	private function _phpunit()
	{
		return $this->_phpunitFramework();
	}

	private function _php()
	{
		return $this->_phpunitFramework();
	}

	private function _test()
	{
		$array_class = $this->_class_array;

		if ($array_class[1] == 'system')
		{
			unset($array_class[0], $array_class[1]);
			return '/system/test/'.implode('/', $array_class).'.php';
		}

		$array_class = $this->_class_array;

		unset($array_class[0]);

		return '/tests/'.implode('/', $array_class).'.php';
	}

	private function _db()
	{
		return $this->_system('db');
	}

	private function _mvc()
	{
		return $this->_system('mvc');
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

	/**
	 * 						!!!ВАЖНО!!!
	 * Определенные ниже методы не относятся к типам классов.
	 * Следовательно типы не должны содержать такие названия.
	 */
	private function _phpunitFramework()
	{
		$array_class = explode('_', $this->_class);
		return '/system/test/'.implode('/', $array_class).'.php';
	}

	private function _system($element)
	{
		$array_class = $this->_class_array;

		unset($array_class[0]);

		return '/system/'.$element.'/'.implode('/', $array_class).'.php';
	}

	private function _module($element)
	{
		$array_class = $this->_class_array;

		$module_name = $array_class[1];
		unset($array_class[0], $array_class[1]);

		return '/modules/'.$module_name.'/'.$element.'/'.implode('/', $array_class).'.php';
	}
}