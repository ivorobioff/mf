<?php
namespace Model\Log;

use \Db\Log\Log as TableLog;

class Log extends \System\Mvc\Model
{
	public function _getTable()
	{
		return new TableLog();
	}

	public function logIt(array $data)
	{
		//needs to be done
	}
}