<?php
namespace Model\Logs;

use System\Db\ActiveRecord;

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
	public function getAll(array $values = array())
	{
		$rules = array(
			'from' => array('insert_date', '>=', '{value} 00:00:00'),
			'to' => array('insert_date', '<=', '{value} 23:59:59')
		);

		$this->_table->filter($values, $rules);

		if ($keyword = always_set($values, 'keyword'))
		{
			$this->_table->match('item_name', $keyword);
		}

		return new Result($this->_table->fetchAll());
	}
}