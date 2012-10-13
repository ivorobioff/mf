<?php
namespace Model\Operations;

use \Db\Operations\Categories as TableCategories;

class Categories
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
		return (bool) $this->_table
			->where('id', $cat_id)
			->getValue('id');
	}

	/**
	 *
	 * @param array $data
	 * @return int - id новой категории
	 */
	public function addCategory(array $data)
	{
		return $this->_table->insert($data);
	}

	public function compare($amount)
	{
		$current_amount = $this->_table
			->select('amount')
			->where('id', $this->_cat_id)
			->getValue('amount', 0);

		return ($amount - $current_amount);
	}

	public function setAmount($amount)
	{
		$this->_table
			->where('id', $this->_cat_id)
			->update(array('amount' => $amount));
	}

	public function setCurrentAmount($amount)
	{
		$this->_table
			->where('id', $this->_cat_id)
			->update(array('current_amount' => $amount));
	}
}