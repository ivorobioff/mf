<?php
namespace Model\Archive;

use Lib\Archive\Row;
use \Model\Common\Archivable;
use \Db\Archive\Archive as TableArchive;
use \System\Mvc\Model as SystemModel;

class Archivator extends SystemModel
{
	private $_arch_items = array();

	protected function _getTable()
	{
		return new TableArchive();
	}

	public function add(Archivable $item)
	{
		$this->_arch_items[$item->getToken()] = $item;
	}

	public function process()
	{
		foreach ($this->_arch_items as $token => $item)
		{
			$data[$token] = serialize($item->prepareData());
		}

		return $this->_save($data);
	}

	private function _save(array $data)
	{
		return $this->_table->insert($data);
	}

	public function notify()
	{
		foreach ($this->_arch_items as $item)
		{
			$item->onSave();
		}
	}

	public function get()
	{
		return new Row(parent::get());
	}

	public function getDates()
	{
		return $this->_table->getHash('id', 'insert_date');
	}
}