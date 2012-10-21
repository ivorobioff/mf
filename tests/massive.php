<?php
namespace Ctest;

use \Plugins\Utils\Massive as UtilMassive;

class Massive extends \PHPUnit_Framework_TestCase
{
	public function testAppyManyRules()
	{
		$data = array(
			array(
				'name' => ' Jonh',
				'nickname' => 'Black,',
				'age' => 'sdfsd'
			),
			array(
				'name' => ' Jonh',
				'nickname' => 'Black,',
				'age' => 'sdfsd'
			)
		);

		$rules = array(
			'name' => function($value){ return trim($value); },
			'nickname' => function($value){ return trim($value, ','); },
			'age' => function($value){ return intval($value); }
		);

		UtilMassive::applyRules($rules, $data);

		$this->assertEquals(array(
			array(
				'name' => 'Jonh',
				'nickname' => 'Black',
				'age' => '0'
			),
			array(
				'name' => 'Jonh',
				'nickname' => 'Black',
				'age' => '0'
			)
		), $data);
	}

	public function testApplyRule()
	{
		$data = array(
			'name' => 'Jonh,',
			'nickname' => 'Black,'
		);

		UtilMassive::applyRules(function($value){ return trim($value, ','); }, $data);

		$this->assertEquals(array(
			'name' => 'Jonh',
			'nickname' => 'Black'
		), $data);
	}
}