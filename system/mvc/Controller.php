<?php
namespace System\Mvc;

use \System\Ajax\Responder as AjaxResponder;
use \System\Ajax\Exception as AjaxException;
use \System\Mvc\View;
use \System\Lib\Http;
/**
 * Абстрактный контроллер.
 * Все стандартные контроллеры должны наследовать этот контроллер. Кроме ажаксовских контроллеров.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class Controller
{
	protected $_view;

	protected $_ajax_responder;

	protected $_default_layout = '';

	public function __construct()
	{
		if (Http::isAjax())
		{
			$this->_ajax_responder = new AjaxResponder();
			return ;
		}

		$this->_view = new View($this->_default_layout);

		$this->_initPage();
	}

	protected function _mustBeAjax()
	{
		if (!Http::isAjax())
		{
			throw new AjaxException();
		}
	}

	protected function _initPage()
	{

	}

	protected function _sendError(array $data = array())
	{
		$this->_ajax_responder->sendError($data);
	}

	protected function _sendResponse(array $data = array())
	{
		$this->_ajax_responder->sendResponse($data);
	}

	protected function _sendExtendedResponse(array $data = array())
	{
		$this->_ajax_responder->sendExtendedResponse($data);
	}

	protected function _render($template_path)
	{
		$this->_view->render($template_path);
	}
}