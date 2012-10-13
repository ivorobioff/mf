<?php
namespace Common\Test;
use \Common\Lib\Validator\Validator as LibValidator;
use \Common\Lib\Validator\Rules\Emptiness;

class Validator extends \PHPUnit_Framework_TestCase
{
	private $_validator;

	public function setUp()
	{
		$this->_validator = new LibValidator();
	}

	public function testEmpty()
	{
		$field = '';
		$error = 'Поле не может быть пустым';

		$this->_validator->setRule(new Emptiness());

		if (!$this->_validator->isValid($field))
		{
			$errors = $this->_validator->fetchErrors();

			$this->assertTrue($errors[0] == $error);
		}
	}
}