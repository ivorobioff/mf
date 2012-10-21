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
			$data = array(
				'title' => '',
				'name' => '',
			);

			$rules = array(
				'title' => new Emptiness('The field "Title" cannot be empty'),
				'name' => array(new Emptiness('The field "Name" cannot be empty')),
			);

			if (!$this->_validator->isValid($data, $rules))
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