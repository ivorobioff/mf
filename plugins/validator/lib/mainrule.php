<?php
namespace Plugins\Validator\Lib;
/**
 * Интерфейс всех правил валидации
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class MainRule
{
	protected $_message = 'Unknown error';

	public function __construct($message = '')
	{
		if ($message)
		{
			$this->_message = $message;
		}
	}

	abstract public function validate();
}