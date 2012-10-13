<?php
/**
 * Абстрактный класс обрабатывает все исключения идущие из контроллеров.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
namespace System\Exceptions;

abstract class Controller extends Exception
{
	final public function load()
	{
		$this->_handler();
	}

	/**
	 * Хендлер, который определяет контроллер способный справится с исключительной ситуацией
	 * @param array $gets
	 * @param array $posts
	 */
	abstract protected function _handler();
}