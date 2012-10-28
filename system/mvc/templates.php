<?php
namespace System\Mvc;
/**
 * Абстрактный класс шаблонизатор.
 * Используется для работы с шаблонами.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class Templates
{
	protected $_params = array();

	protected function _render($template_path, $params = array())
	{
		extract($params);

		include_once '/front/templates/'.$template_path;
	}

	public function __get($name)
	{
		if (!isset($this->_params[$name]))
		{
			throw new \System\Exceptions\Exception('Нет такого параметра.');
		}

		return $this->_params[$name];
	}

	public function __set($name, $value)
	{
		$this->_params[$name] = $value;
	}
}