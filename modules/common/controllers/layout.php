<?php
namespace Controller\Common;

use \Controller\Common\Helpers\Layout as HelperLayout;
use \System\Lib\Http;

class Layout extends \System\Mvc\Controller
{
	protected $_default_layout = 'common/main.phtml';

	protected $_page_titles = array(
		'operations/flow/index' => 'Денежный поток',
		'operations/planner/index' => 'Планировщик',
		'log/index/index' => 'Логи'
	);

	protected function _initPage()
	{
		HelperLayout::minimizeJavaScript();

		$this->_view->page_title = always_set($this->_page_titles, trim(Http::location(), '/'), '');
		$this->_view->title = 'MF (alpha) : '.$this->_view->page_title;

		$this->_view->budget = json_encode(HelperLayout::getBudgetData());
	}
}