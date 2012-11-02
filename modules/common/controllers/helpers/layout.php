<?php
namespace Controller\Common\Helpers;

use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Categories as ModelCategories;
use \Plugins\Minimizer\Minimizer;
use \Plugins\Minimizer\Exception as MinException;
use \Lib\Common\Utils;

class Layout
{
	static public function getBudgetData()
	{
		$budget = new ModelBudget(1);
		$cats = new ModelCategories();

		$expenses = $cats->getExpenses();
		$budget_amount = $budget->getAmount();

		return array(
			'budget' => Utils::toMoney($budget_amount),
			'expenses' => Utils::toMoney($expenses),
			'remainder' => Utils::toMoney($budget_amount - $expenses)
		);
	}

	static public function minimizeJavaScript()
	{
		$minimize = new Minimizer('/front/min_config.xml');

		try
		{
			$minimize->process(false);
		}
		catch (MinException $ex)
		{
			die($ex->getMessage());
		}
	}
}