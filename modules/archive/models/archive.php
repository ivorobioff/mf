<?php
namespace Model\Archive;

use Db\Archive\Archive as TableArchive;
use System\Mvc\Model as SystemModel;

class Archive extends SystemModel
{
	protected function _getTable()
	{
		return new TableArchive();
	}
}