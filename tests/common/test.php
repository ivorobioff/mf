<?php
namespace Test\Common;
use \Db\Manipulator\MCategories;

class Test extends \PHPUnit_Framework_TestCase
{
	private $_table;

	public function setUp()
	{
		$this->_table = new MCategories();
		$this->_table->db()->query('TRUNCATE TABLE test');
	}

	public function testConnection()
	{
		$this->assertTrue(strpos($this->_table->db()->client_info, 'mysqlnd') !== false);
	}

	public function testInsert()
	{
		try {
			$this->_table->insert(array('first_name' => 'Igor', 'last_name' => 'Vorobioff'));

			$res = $this->_table->db()->query('SELECT * FROM test');

			$row = $res->fetch_assoc();

			$this->assertEquals($row['first_name'], 'Igor');
			$this->assertEquals($row['last_name'], 'Vorobioff');
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}
}