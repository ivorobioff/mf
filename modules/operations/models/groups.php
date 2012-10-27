<?php
namespace Model\Operations;

use \Db\Operations\Groups as TableGroups;
use \Db\Operations\Categories as TableCategories;
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

	public function isEmpty()
	{
		$table = new TableCategories();

		$res = $table
			->select('COUNT(*) AS total')
			->where('group_id', $this->_group_id)
			->getValue('total', 0);

		return $res ? false : true;
	}

	public function delete()
	{
		return $this->_table->delete('id', $this->_group_id);
	}
}