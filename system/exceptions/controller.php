<?php
/**
 * Класс обрабатывает все исключения идущие от контроллеров.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
namespace System\Exceptions;

class Controller extends \System\Exceptions\Exception
{

	public function stop()
	{
		die($this->getMessage());
	}
}