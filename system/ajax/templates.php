<?php
namespace System\Ajax;
/**
 * Класс представляющий html шаблоны. Используется для ajax ответов.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Templates extends \System\Mvc\Templates
{
	private $_template_path = '';

	public function __construct($template_path)
	{
		$this->_template_path = $template_path;
	}

	public function render()
	{
		ob_start();
		parent::_render($this->_template_path);
		return ob_get_clean();
	}
}