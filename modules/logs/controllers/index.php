<?php
namespace Controller\Logs;

use \Controller\Common\Layout;
use \Model\Logs\Logs as ModelLogs;

class Index extends Layout
{
	public function index()
	{
		$logs = new ModelLogs();

		$this->_view->logs = json_encode($logs->getAll()->toArray());

		$this->_render('logs/index.phtml');
	}
}