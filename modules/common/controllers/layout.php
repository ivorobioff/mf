<?php
namespace Controller\Common;

use \Controller\Common\Helpers\Layout as HelperLayout;

class Layout extends \System\Mvc\Controller
{
	protected $_default_layout = 'common/main.phtml';

	protected function _initPage()
	{
		HelperLayout::minimizeJavaScript();

		$this->_view->title = 'Money Flow 1.0';

		$this->_view->budget = json_encode(HelperLayout::getBudgetData());
	}
}