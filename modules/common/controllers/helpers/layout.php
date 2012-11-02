<?php
namespace Controller\Common\Helpers;

use \Model\Operations\Budget as ModelBudget;
use \Model\Operations\Categories as ModelCategories;
use \Plugins\Minimizer\Minimizer;
use \Plugins\Minimizer\Exception as MinException;

class Layout
{
	static public function getBudgetData()
	{
		$budget = new ModelBudget(1);
		return $budget->getStatistics();
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