<?php
namespace Ctest;
use \Plugins\Validator\Validator as LibValidator;
use \Plugins\Validator\Rules\Emptiness;

class Validator extends \PHPUnit_Framework_TestCase
{
	private $_validator;

	public function setUp()
	{
		$this->_validator = new LibValidator();
	}

	public function testEmpty()
	{
		try
		{
			$field = '';
			$error = 'Поле не может быть пустым';

			$this->_validator->setRule(new Emptiness());

			if (!$this->_validator->isValid($field))
			{
				$errors = $this->_validator->fetchErrors();
				$this->assertTrue(count($errors) > 0);
			}
		}
		catch(\Exception $ex)
		{
			die($ex->getMessage());
		}
	}
}