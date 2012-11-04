<?php
namespace Model\Operations;

use \Db\Operations\Categories as TableCategories;

class Categories extends \System\Mvc\Model
{
	public function __construct($cat_id = null)
	{
		parent::__construct($cat_id);

		$this->_table = new TableCategories();
		$this->_id = $cat_id;
	}


	public function edit(array $data = array())
	{
		unset($data['id']);

		return $this->_table
			->where($this->_id_key, $this->_id)
			->update($data);
	}

	public function delete()
	{
		return $this->_table->delete($this->_id_key, $this->_id);
	}

	/**
	 * @param array $data
	 * @return int - id новой категории
	 */
	public function add(array $data)
	{
		return $this->_table->insert($data);
	}

	public function withdrawal($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('current_amount=current_amount-', $amount);
	}

	public function deposit($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('current_amount=current_amount+', $amount);
	}

	public function requestAmount($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update(array(
				'amount=amount+' => $amount,
				'current_amount' => 0
			));
	}

	public function returnAmount()
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('amount=amount-current_amount, current_amount=0');
	}
}