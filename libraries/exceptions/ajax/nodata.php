<?php
namespace Common\Lib\Exceptions\Ajax;

use \System\Ajax\Responder as AjaxResponder;

class NoData extends \System\Exceptions\Controller
{
	private $_message;

	public function __construct($message)
	{
		parent::__construct();

		$this->_message = $message;
	}
	protected function _handler()
	{
		$responder = new AjaxResponder();
		$responder
			->attachData(array('message' => $this->_message))
			->sendError();
	}
}