<?php
namespace Model\Operations;

use \Db\Operations\Groups as TableGroups;

class Groups
{
	private $_table;

	private $_group_id;

	public function __construct($group_id = null)
	{
		$this->_group_id = $group_id;

		$this->_table = new TableGroups();
	}

	public function getAll()
	{
		return $this->_table->fetchAll();
	}

	public function add(array $data)
	{
		return $this->_table->insert($data);
	}

	public function edit(array $data)
	{
		unset($data['id']);

		return $this->_table
			->where('id', $this->_group_id)
			->update($data);
	}
}