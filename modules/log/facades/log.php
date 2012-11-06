<?php
namespace Facade\Log;

use \Model\Log\Log as ModelLog;

class Log
{
	static public function logIt(array $data)
	{
		$log = new ModelLog();
		$log->logIt($data);

	}
}