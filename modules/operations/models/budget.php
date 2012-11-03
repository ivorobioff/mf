<?php
namespace Model\Operations;

use \Db\Operations\Budget as TableBudget;
use \Db\Operations\Categories as TableCategories;
use \Lib\Common\Utils;

class Budget extends \System\Db\Model
{
	public function __construct($id = null)
	{
		parent::__construct($id);
		$this->_table = new TableBudget();
	}

	public function getExpenses()
	{
		$cats_table = new TableCategories();
		return $cats_table->select('SUM(amount) AS expenses')->getValue('expenses', '0');
	}

	public function getStatistics()
	{
		$budget = $this->getAmount();
		$expenses = $this->getExpenses();

		return array(
			'budget' => Utils::toMoney($budget),
			'expenses' => Utils::toMoney($expenses),
			'remainder' => Utils::toMoney( $budget - $expenses)
		);
	}

	public function withdrawal($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('amount=amount-', $amount);
	}

	public function deposit($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('amount=amount+', $amount);
	}
}