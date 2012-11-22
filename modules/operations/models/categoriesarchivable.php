<?php
namespace Model\Operations;

use Model\Common\Archivable;
use Model\Operations\Categories as ModelCategories;
class CategoriesArchivable extends Archivable
{
	public function prepareData()
	{
		$cats = new ModelCategories();
		return $cats->getAll();
	}

	public function getToken()
	{
		return 'categories';
	}
}