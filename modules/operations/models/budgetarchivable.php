<?php
namespace Model\Operations;

use \Model\Common\Archivable;
use \Db\Operations\Budget as TableBudget;
use \Model\Operations\Budget as ModelBudget;

class BudgetArchivable extends Archivable
{
	public function prepareData()
	{
		return ModelBudget::getInstance()->getSummary();
	}

	public function getToken()
	{
		return 'budget';
	}
}