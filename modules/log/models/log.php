<?php
namespace Model\Log;

use \Db\Log\Log as TableLog;

class Log extends \System\Mvc\Model
{
	public function _getTable()
	{
		return new TableLog();
	}

	public function log(array $data)
	{
		$prepared_data = array();

		$prepared_data[] = array_merge(array('position' => 'before'), $data['before'], $data['common']);
		$prepared_data[] = array_merge(array('position' => 'after'), $data['after'], $data['common']);

		return $this->_table->insertAll($prepared_data);
	}
}