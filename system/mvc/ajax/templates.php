<?php
namespace System\Mvc\Ajax;

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