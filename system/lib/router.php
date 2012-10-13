<?php
namespace System\Lib;
/**
 * Рутер.
 * Класс управляет приходящими запросами.
 * Создает нужный объект контроллера и передает ему контроль над системой.
 *
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Router
{
	const DEFAULT_PATH = 'manipulator/index/index';

	private $_array_path;

	static private $_instance;

	public function __construct($url)
	{
		$this->_parseUrl($url);
	}

	static public function getInstance()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = new self($_SERVER['REQUEST_URI']);
		}

		return self::$_instance;
	}

	/**
	 * Парсит url
	 * @param string $url
	 */
	private function _parseUrl($url)
	{
		$path = trim(trim($url), '/');

		if ($path == '')
		{
			$path = self::DEFAULT_PATH;
		}

		$path = explode('?', $path);

		$query = always_set($path, 1, false);

		$path = $path[0];

		if ($query)
		{
			parse_str($query, $_GET);
		}

		$path = trim(trim($path), '/');

		$this->_array_path = explode('/', $path);
	}

	/**
	 * Возвращает тип класса
	 * @return string
	 */
	private function _getType()
	{
		return $this->_array_path[0];
	}


	/**
	 * Проверяет если запрос ajax
	 * @return boolean
	 */
	public function isAjax()
	{
		return $this->_getType() == 'ajax';
	}

	/**
	 * Создает и передает управление стандартным контроллерам
	 * @throws \System\Exceptions\Error404 - в случае если контроллер или действие не найдено
	 */
	private function _standardController()
	{
		list ($module_name, $controller_name, $action_name) = $this->_getMainParts();

		$controller_file = root_path().'/modules/'.$module_name.'/controllers/'.$controller_name.'.php';

		if (!file_exists($controller_file))
		{
			throw new \System\Error404\Exception();
		}

		$controller_class = 'Controller\\'.$module_name.'\\'.ucfirst($controller_name);

		$controller = new $controller_class();

		if (!method_exists($controller, $action_name))
		{
			throw new \System\Error404\Exception();
		}

		try
		{
			$controller->{$action_name}();
		}
		catch (\System\Exceptions\Controller $ex)
		{
			$ex->load();
		}
	}

	/**
	 * Выделяем основные части запроса
	 * @return array
	 */
	private function _getMainParts()
	{
		$i = 0;

		if ($this->isAjax())
		{
			$i++;
		}

		return array(
			$this->_array_path[$i],
			always_set($this->_array_path, $i = $i + 1, 'index'),
			$this->_prepareAction(always_set($this->_array_path, $i = $i + 1, 'index'))
		);
	}

	/**
	 * Переделывает формат строки в формат функции. Например: set-action становится setAction
	 * @param string $action
	 */
	private function _prepareAction($action)
	{
		$action = explode('-', strtolower($action));

		foreach ($action as $k => $v)
		{
			$action[$k] = ucfirst($v);
		}

		$action[0] = strtolower($action[0]);

		return implode('', $action);
	}

	/**
	 * Создает и передает управление контроллеру тестов
	 * @throws \System\Exceptions\Error404
	 */
	private function _testController()
	{
		$module_name = always_set($this->_array_path, 1);
		$test_case_name = always_set($this->_array_path, 2);

		if (!$module_name || !$test_case_name)
		{
			throw new \System\Exceptions\Error404();
		}

		$test_case_path = root_path().'/modules/'.$module_name.'/tests/'.$test_case_name.'.php';
		$test_case_class = 'Test\\'.ucfirst($module_name).'\\'.ucfirst($test_case_name);


		// переопределяем модуль для общих тестов.
		if ($module_name == 'common')
		{
			$test_case_path = root_path().'/tests/'.$test_case_name.'.php';
			$test_case_class = 'Common\Test\\'.ucfirst($test_case_name);
		}

		if (!file_exists($test_case_path))
		{
			throw new \System\Exceptions\Error404();
		}

		$controller_class = 'System\Test\Controller';

		$controller = new $controller_class($test_case_class);

		$controller->{'run'}();
	}

	/**
	 * Запускает механизм рутинга.
	 * Вначале проверяет если есть какие-то зарезервированные типы контроллеров.
	 * Если нет, то создает стандартный контроллер.
	 */
	public function run()
	{
		$method = '_'.$this->_getType().'Controller';

		if (method_exists($this, $method))
		{
			$this->$method();
			return true;
		}

		$this->_standardController();
	}
}