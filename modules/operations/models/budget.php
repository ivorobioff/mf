<?php
namespace Model\Operations;

use \Db\Operations\Budget as TableBudget;

class Budget extends \System\Db\Model
{
	public function __construct($id = null)
	{
		parent::__construct($id);
		$this->_table = new TableBudget();
	}
}