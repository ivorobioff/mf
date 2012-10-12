<?php
/**
 * Класс управляет приходящими запросами.
 * Создает нужный объект контроллера и передает ему управление системой.
 *
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Router
{
	const DEFAULT_PATH = 'manipulator/index/index';

	private $_array_path;

	public function __construct($url)
	{
		$this->_parseUrl($url);
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
	 * Создает и передает управление стандартным контроллерам
	 * @throws \System\Exceptions\Error404 - в случае если контроллер или действие не найдено
	 */
	private function _standardController()
	{
		$module_name = $this->_array_path[0];
		$controller_name = always_set($this->_array_path, 1, 'index');
		$action_name = $this->_prepareAction(always_set($this->_array_path, 2, 'index'));

		$controller_file = root_path().'/modules/'.$module_name.'/controllers/'.$controller_name.'.php';

		if (!file_exists($controller_file))
		{
			throw new \System\Exceptions\Error404();
		}

		$controller_class = 'Controller\\'.$module_name.'\\'.ucfirst($controller_name);

		$controller = new $controller_class();

		if (!method_exists($controller, $action_name))
		{
			throw new \System\Exceptions\Error404();
		}

		try
		{
			$controller->{$action_name}($_GET, $_POST);
		}
		catch (\System\Exceptions\Controller $ex)
		{
			$ex->stop();
		}
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

		$test_case_path = root_path().'/tests/'.$module_name.'/'.$test_case_name.'.php';

		if (!file_exists($test_case_path))
		{
			throw new \System\Exceptions\Error404();
		}

		$controller_class = 'System\Test\Controller';

		$controller = new $controller_class('Test\\'.ucfirst($module_name).'\\'.ucfirst($test_case_name));

		$controller->{'run'}();
	}

	/**
	 * Запускает механизм рутинга
	 */
	public function run()
	{
		if ($this->_getType() == 'test')
		{
			$this->_testController();
		}

		$this->_standardController();
	}
}