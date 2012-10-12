<?php
namespace Model\Operations;

use \Db\Operations\Categories as TableCategories;

class Planner
{
	private $_cat_id;

	private $_table;

	public function __construct($cat_id = null)
	{
		$this->_table = new TableCategories();
		$this->_cat_id = $cat_id;
	}

	public function categoryExists($cat_id)
	{

	}

	public function compare($amount)
	{
		$current_amount = $this->_table
			->select('amount')
			->fetchOne('id', $this->_cat_id);

		return ($amount - $current_amount['amount']);
	}

	public function setAmount($amount)
	{
		$this->_table
			->where('id', $this->_cat_id)
			->update(array('amount' => $amount));
	}
}