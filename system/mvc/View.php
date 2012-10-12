<?php
namespace System\Mvc;
/**
 * Класс для создания и контроля веб-страниц.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
final class View
{
	private $_layout_template;
	private $_view_template;
	private $_params = array();

	public function __construct($template_path)
	{
		$this->_layout_template = $template_path;
	}

	public function render($template_path)
	{
		$this->_view_template = $template_path;
		include_once '/front/templates/'.$this->_layout_template;
	}

	private function _getView()
	{
		include_once '/front/templates/'.$this->_view_template;
	}

	private function _html($template_path)
	{
		ob_start();
		include_once 'front/templates/'.$template_path;
		return ob_get_clean();
	}

	public function setLayout($template_path)
	{
		$this->_layout_template = $template_path;
	}

	public function __get($name)
	{
		if (!isset($this->_params[$name]))
		{
			throw new \Exception('Нет такого параметра.');
		}

		return $this->_params[$name];
	}

	public function __set($name, $value)
	{
		$this->_params[$name] = $value;
	}
}