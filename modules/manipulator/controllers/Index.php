<?php
namespace Controller\Manipulator;

class Index extends \Mvc\Controller
{
	public function index()
	{
		$this->_getView()->name = 'Igor';
		$this->_getView()->render('manipulator/default.phtml');
	}
}
