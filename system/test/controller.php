<?php
namespace Test\System;

class Controller
{
	private $_class_name;

	public function __construct($class_name)
	{
		$this->_class_name = $class_name;
	}

	public function run()
	{
		$c = new $this->_class_name();
	}
}