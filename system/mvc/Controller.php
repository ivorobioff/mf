<?php
namespace System\Mvc;

use \System\Ajax\Responder as AjaxResponder;
use \System\Ajax\Exception as AjaxException;
use \System\Mvc\View;
use \System\Lib\Http;
use \Plugins\Minimizer\Minimizer;
use \Plugins\Minimizer\Exception as MinException;
use \System\Lib\Server;
/**
 * Абстрактный контроллер.
 * Все стандартные контроллеры должны наследовать этот контроллер. Кроме ажаксовских контроллеров.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class Controller
{
	protected $_view;

	protected $_ajax_responder;

	protected $_default_layout = 'common/main.phtml';

	public function __construct()
	{
		if (Http::isAjax())
		{
			$this->_ajax_responder = new AjaxResponder();
			return ;
		}

		$this->_initPage();
	}

	protected function _mustBeAjax()
	{
		if (!Http::isAjax())
		{
			throw new AjaxException();
		}
	}

	private function _initPage()
	{
		$this->_view = new View($this->_default_layout);
		$this->_view->title = 'Money Flow 1.0';

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