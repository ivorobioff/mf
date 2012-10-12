<?php
namespace Model\Operations;

use \Db\Operations\Budget as TableBudget;

class Budget
{
	private $_table;

	public function __construct()
	{
		$this->_table = new TableBudget();
	}


	public function addAmount($amount = 0)
	{
		$this->_table
			->update(array('amount=amount+', $amount));
	}
}