<?php
namespace Plugins\Validator\Lib;
/**
 * Класс для выброса ошибки при валидации
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Exception extends \Exception
{
	private $_error = '';

	public function __construct($error)
	{
		parent::__construct();

		$this->_error = $error;
	}

	public function getError()
	{
		return $this->_error;
	}
}