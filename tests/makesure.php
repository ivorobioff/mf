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
}