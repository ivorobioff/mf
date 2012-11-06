<?php
namespace Model\Operations;

use \Db\Operations\Categories as TableCategories;

class Categories extends \System\Mvc\Model
{
	protected function _getTable()
	{
		return new TableCategories();
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
		$res =  $this->_table
			->where($this->_id_key, $this->_id)
			->update(array(
				'current_amount=current_amount-' => $amount,
			));

		return $res;
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

	public function getTotalPlanned()
	{
		return $this->_table->
			select('SUM(amount) AS total')->getValue('total');
	}

	/**
	 * Проверка если категорию можно удалить
	 */
	public function canDelete()
	{
		$item = $this->get();

		return $item['current_amount'] == $item['amount'];
	}
}