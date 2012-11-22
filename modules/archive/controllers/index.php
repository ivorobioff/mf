<?php
namespace Controller\Archive;

use Model\Operations\BudgetArchivable;
use Model\Operations\CategoriesArchivable;
use Model\Logs\LogsArchivable;

use Controller\Common\Layout;
use \Model\Archive\Archivator;

class Index extends Layout
{
	const ARCHIVE_NOT_PROCESSED = 'Данные небыли сохраненны в архив.';

	public function Index()
	{
		$archivator = new Archivator();

		$this->_view->archive_dates = $archivator->getDates();

		$this->_render('archive/index.phtml');
	}

	public function newMonth()
	{
		$archivator = new Archivator();

		$archivator->add(new BudgetArchivable());
		$archivator->add(new CategoriesArchivable());
		$archivator->add(new LogsArchivable());

		if (!$archivator->process())
		{
			return $this->_sendError(array(self::ARCHIVE_NOT_PROCESSED));
		}

		$archivator->notify();
	}
}