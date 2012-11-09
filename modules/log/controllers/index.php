<?php
namespace Controller\Log;

use \Controller\Common\Layout;

class Index extends Layout
{
	public function index()
	{
		$this->_render('log/index.phtml');
	}
}