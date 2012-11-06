<?php
namespace Model\Operations;

use \Db\Operations\Budget as TableBudget;
use \Model\Operations\Categories as ModelCategories;
use \Lib\Common\Utils;

class Budget extends \System\Mvc\Model
{
	protected function _getTable()
	{
		return new TableBudget();
	}

	public function getSummary()
	{
		$budget = $this->get();

		$cats = new ModelCategories();
		$total_planned = $cats->getTotalPlanned();

		return array(
			'budget' => Utils::toMoney($budget['income'] - $budget['real_expenses']),
			'expenses' => Utils::toMoney($total_planned),
			'remainder' => Utils::toMoney($budget['income'] - $total_planned)
		);
	}

	public function withdrawal($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('income=income-', $amount);
	}

	public function deposit($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('income=income+', $amount);
	}

	public function addRealExpenses($amount)
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->update('real_expenses=real_expenses+', $amount);
	}
}