<?php
namespace Ctest;
use Plugins\Validator\Rules\DateFormat;

use Plugins\Validator\Rules\IsDefined;

use \Plugins\Validator\Validator as LibValidator;
use \Plugins\Validator\Rules\Emptiness;

class Validator extends \PHPUnit_Framework_TestCase
{
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

			if (!LibValidator::isValid($data, $rules))
			{
				$errors = LibValidator::fetchErrors();
				$this->assertTrue(count($errors) > 0);
			}
		}
		catch(\Exception $ex)
		{
			die($ex->getMessage());
		}
	}

	public function testIsDefined()
	{
		$data = array(
			'name' => 'Wow',
			'date' => '2001-23-12'
		);

		$rules = array(
			'name' => new Emptiness('The field is empty'),
			'not_set' => true,
		);

		if (!LibValidator::isValid($data, $rules))
		{
			$errors = LibValidator::fetchErrors();
			$this->assertTrue(count($errors) == 1);
		}
	}

	public function testWrongDateFormat()
	{
		$data = array(
			'date' => '12-13-2012'
		);

		$rules = array(
			'date' => new DateFormat('d-m-Y', 'Wrong date')
		);

		if (!LibValidator::isValid($data, $rules))
		{
			$errors = LibValidator::fetchErrors();
			$this->assertTrue(count($errors) == 1);
			return ;
		}

		$this->fail('Not Passed');
	}

	public function testCorrectDateFormat()
	{
		$data = array(
				'date' => '12-10-2012'
		);

		$rules = array(
				'date' => new DateFormat('d-m-Y', 'Wrong date')
		);

		if (!LibValidator::isValid($data, $rules))
		{
			$this->fail('Not Passed');
		}

		$this->assertTrue(true);

	}
}