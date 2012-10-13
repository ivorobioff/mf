<?php
namespace System\Mvc\Ajax;
/**
 * Абстрактный контроллер для ajax запросов.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class Controller
{
	private $_ajax;

	public function __construct()
	{
		$this->_ajax = new Ajax();
	}

	/**
	 * Получает объект ajax отправителя.
	 * @return System\Mvc\Ajax\Ajax
	 */
	protected function _getAjax()
	{
		return $this->_ajax;
	}
}