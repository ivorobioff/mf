<?php
namespace Model\Operations;

use \Db\Operations\Groups as TableGroups;
use \Db\Operations\Categories as TableCategories;

class Groups extends \System\Db\Model
{
	public function __construct($group_id = null)
	{
		$this->_id = $group_id;

		$this->_table = new TableGroups();
	}

	public function add(array $data)
	{
		return $this->_table->insert($data);
	}

	public function edit(array $data)
	{
		unset($data['id']);

		return $this->_table
			->where($this->_id_key, $this->_id)
			->update($data);
	}

	public function isEmpty()
	{
		$table = new TableCategories();

		$res = $table
			->select('COUNT(*) AS total')
			->where('group_id', $this->_id)
			->getValue('total', 0);

		return $res ? false : true;
	}

	public function delete()
	{
		return $this->_table->delete($this->_id_key, $this->_id);
	}
}