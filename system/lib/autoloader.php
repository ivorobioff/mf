<?php
/**
 * Класс для autoloading.
 * В зависимости от типа класса выберается путь к файлу с классом.
 * Каждый тип класс имеет соответствующий метод для получения пути.
 * @author Igor
 */
class Autoloader
{
	private $_class_type;

	private $_class_array;

	private $_original_class;

	static private $_instance;

	static public function getInstance()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Возвращает путь к файлу с заданым классом
	 * @param string $class
	 * @return string - путь к файлу
	 */
	public function getPath($class)
	{
		$this->_original_class = $class;

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

	/**
	 * Классы PHPUnit
	 * @return string
	 */
	private function _phpunit()
	{
		return $this->_phpunitFramework();
	}

	/**
	 * Нужные классы для PHPUnit
	 * @return string
	 */
	private function _php()
	{
		return $this->_phpunitFramework();
	}

	/**
	 * Системные классы.
	 * @return string
	 */
	private function _system()
	{
		$array_class = $this->_class_array;

		unset($array_class[0]);

		return '/system/'.implode('/', $array_class).'.php';
	}


	private function _plugins()
	{
		return $this->_globals('plugins');
	}

	/**
	 * Класс общих тестов
	 * @return string
	 */
	private function _ctest()
	{
		 return $this->_globals('tests');
	}

	/**
	 * Классы тест кейсов в модулях
	 * @return string
	 */
	private function _test()
	{
		return $this->_module('tests');
	}

	/**
	 * Классы фасадов
	 * @return string
	 */
	private function _facade()
	{
		return $this->_module('facades');
	}

	/**
	 * Классы таблиц
	 * @return string
	 */
	private function _db()
	{
		return $this->_module('db');
	}

	/**
	 * Классы контроллеров
	 * @return string
	 */
	private function _controller()
	{
		return $this->_module('controllers');
	}

	/**
	 * Классы моделей
	 * @return string
	 */
	private function _model()
	{
		return $this->_module('models');
	}

	/**
	 * Классы библиотек в модулях
	 * @return string
	 */
	private function _lib()
	{
		return $this->_module('libraries');
	}

	/**
	 * 						!!!ВАЖНО!!!
	 * Определенные ниже методы не относятся к типам классов.
	 * Следовательно типы не должны содержать такие названия.
	 */
	private function _globals($element)
	{
		$array_class = $this->_class_array;

		unset($array_class[0]);

		return '/'.$element.'/'.implode('/', $array_class).'.php';
	}

	private function _phpunitFramework()
	{
		$array_class = explode('_', $this->_original_class);
		return '/system/test/'.implode('/', $array_class).'.php';
	}

	private function _module($element)
	{
		$array_class = $this->_class_array;

		$module_name = $array_class[1];
		unset($array_class[0], $array_class[1]);

		return '/modules/'.$module_name.'/'.$element.'/'.implode('/', $array_class).'.php';
	}
}