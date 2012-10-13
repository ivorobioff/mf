<?php
namespace System\Mvc;
/**
 * Класс для создания и контроля веб-страниц.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
final class View extends Templates
{
	private $_layout_template;
	private $_view_template;

	public function __construct($template_path)
	{
		$this->_layout_template = $template_path;
	}

	public function render($template_path)
	{
		$this->_view_template = $template_path;
		parent::_render($this->_layout_template);
	}

	private function _getView()
	{
		parent::_render($this->_view_template);
	}

	private function _html($template_path)
	{
		ob_start();
		parent::_render($template_path);
		return ob_get_clean();
	}

	public function setLayout($template_path)
	{
		$this->_layout_template = $template_path;
	}
}