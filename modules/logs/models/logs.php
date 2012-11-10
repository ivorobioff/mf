<?php
namespace Model\Logs;

use \Db\Logs\Logs as TableLogs;
use \Lib\Logs\Results\Result;

class Logs extends \System\Mvc\Model
{
	public function _getTable()
	{
		return new TableLogs();
	}

	public function log(array $data)
	{
		return $this->_table->insert($data);
	}

	/**
	 * Получаем объект с рузультатом логов
	 * @see System\Mvc.Model::getAll()
	 * @return \Lib\Logs\Results\Result;
	 */
	public function getAll()
	{
		return new Result(parent::getAll());
	}
}