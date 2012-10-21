<?php
namespace Plugins\Validator\Lib;
/**
 * Интерфейс всех правил валидации
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class MainRule
{
	protected $_message;

	public function __construct($message = 'Unknown error')
	{
		$this->_message = $message;
	}

	abstract public function validate();
}