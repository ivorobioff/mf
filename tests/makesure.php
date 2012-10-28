<?php
namespace Ctest;

class MakeSure extends \PHPUnit_Framework_TestCase
{
	public function testRef()
	{
		$v = 10;

		$a = &$v;

		$this->assertTrue($a == 10);

		$a = 15;

		$this->assertTrue($v == 15);
	}

	public function testCamelCaseToUnderScore()
	{
		$v = 'CurrentAmount';

		$res = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $v));

		$this->assertEquals('current_amount', $res);
	}
}