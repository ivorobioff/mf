<?php
namespace Mvc;
use Mvc\View;

class Controller
{
	private $_view;

	protected $_default_layout = 'common/main.phtml';

	public function __construct()
	{
		$this->_view = new View($this->_default_layout);
		$this->_view->title = 'Money Flow 1.0';
	}

	protected function _getView()
	{
		return $this->_view;
	}
}