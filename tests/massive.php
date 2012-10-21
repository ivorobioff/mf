<?php
namespace Ctest;

use \Plugins\Utils\Massive as UtilMassive;

class Massive extends \PHPUnit_Framework_TestCase
{
	public function testAppyManyRules()
	{
		$data = array(
			'name' => ' Jonh',
			'nickname' => 'Black,',
			'age' => 'sdfsd'
		);

		$rules = array(
			'name' => function($value){ return trim($value); },
			'nickname' => function($value){ return trim($value, ','); },
			'age' => function($value){ return intval($value); }
		);

		UtilMassive::applyRules($data, $rules);

		$this->assertEquals(array(
			'name' => 'Jonh',
			'nickname' => 'Black',
			'age' => '0'),
		$data);
	}

	public function testApplyRule()
	{
		$data = array(
			'name' => 'Jonh,',
			'nickname' => 'Black,'
		);

		UtilMassive::applyRules($data, function($value){ return trim($value, ','); });

		$this->assertEquals(array(
			'name' => 'Jonh',
			'nickname' => 'Black'
		), $data);
	}
}