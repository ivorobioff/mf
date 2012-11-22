<?php
namespace Model\Logs;

use \Model\Common\Archivable;
use \Model\Logs\Logs as ModelLogs;

class LogsArchivable extends Archivable
{
	public function prepareData()
	{
		$logs = new ModelLogs();

		return $logs->getAll()->toArray();
	}

	public function getToken()
	{
		return 'logs';
	}
}