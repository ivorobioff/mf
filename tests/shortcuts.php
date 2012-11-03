<?php
namespace Ctest;

class Shortcuts extends \PHPUnit_Framework_TestCase
{
	public function testIsAllSet()
	{
		$data = array('name' => 'John', 'date' => '2011-22-13');

		$this->assertFalse(is_all_set($data, array('name', 'test', 'date')));

		$data = array('name' => 'John', 'date' => '2011-22-13', 'test' => 'yes');

		$this->assertTrue(is_all_set($data, array('name', 'test', 'date')));
	}
}