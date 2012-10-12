<?php
namespace Common\Test;
use \Common\Lib\Validator\Validator as LibValidator;

class Validator extends \PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		try
		{
			$val = new LibValidator();
			$this->assertTrue($val instanceof LibValidator);
		}
		catch (\Exception $ex)
		{
			die($ex->getMessage());
		}
	}
}