<?php
namespace Controller\Operations;

use \Model\Operations\Categories as ModelCategories;
use \Model\Operations\Groups as ModelGroups;

class Flow extends \System\Mvc\Controller
{
	public function index()
	{

		$model_groups = new ModelGroups();
		$model_categories = new ModelCategories();

		$this->_view->groups = json_encode($model_groups->getAll());
		$this->_view->categories = json_encode($model_categories->getAll());

		$this->_view->render('operations/flow/index.phtml');
	}
}