<?php
namespace Controller\Archive;

use Plugins\Utils\MasterArray;

use System\Lib\Http;
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

	public function createArchive()
	{
		$this->_mustBeAjax();

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

	public function readArchive()
	{
		$this->_mustBeAjax();

		$archivator = new Archivator(Http::post('key'));

		$data = $archivator->get();

		$result = new MasterArray(array(
			'categories' => array(),
			'budget' => array(),
			'logs' => array()
		));

		foreach ($result->keys() as $item)
		{
			if ($value = always_set($data, $item))
			{
				$result[$item] = $value;
			}
		}

		return $this->_sendExtendedResponse($result->toArray());

	}
}