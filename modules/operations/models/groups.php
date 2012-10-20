<?php
namespace Model\Operations;

use \Db\Operations\Groups as TableGroups;

class Groups
{
	private $_table;

	public function __construct()
	{
		$this->_table = new TableGroups();
	}

	public function getAll()
	{
		return $this->_table->fetchAll();
	}
}