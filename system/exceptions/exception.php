<?php
namespace System\Exceptions;
/**
 * Абстрактный класс исключений.
 * Все классы исключений должны наследовать данный класс.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
abstract class Exception extends \Exception
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function log()
	{
		echo $this->getMessage();
	}
}