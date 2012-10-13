<?php
/**
 * Класс обрабатывает все исключения идущие от контроллеров.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
namespace System\Exceptions;

abstract class Controller extends Exception
{
	private $gets = array();
	private $posts = array();

	public function __construct(array $gets = array(), array $posts = array())
	{
		$this->_gets = $gets;
		$this->_posts = $posts;
	}

	final public function load()
	{
		$this->_handler($this->_gets, $this->_posts);
	}

	abstract protected function _handler(array $gets, array $posts);
}