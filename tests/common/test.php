<?php
namespace Test\Common;

class Test extends \PHPUnit_Framework_TestCase
{
	private $_table;
	private $_table2;

	public function setUp()
	{
		$this->_table = new  \Db\Manipulator\Test();
		$this->_table2 = new \Db\Manipulator\Test2();
		$this->_table->db()->query('TRUNCATE TABLE test');
		$this->_table2->db()->query('TRUNCATE TABLE test2');
	}

	public function testConnection()
	{
		$this->assertTrue(strpos($this->_table->db()->client_info, 'mysqlnd') !== false);
	}

	public function testInsertAndDuplicate()
	{
		try
		{
			$this->_table
				->insert(
					array(
						'id' => 1,
						'first_name' => 'Igor',
						'last_name' => 'Vorobioff',
						'number' => 1
					)
				);

			$this->_table
				->duplicate('id=id+', 1)
				->insert(
					array(
						'id' => 1,
						'first_name' => 'Igor',
						'last_name' => 'Vorobioff',
						'number' => 1
					)
				);

			$res = $this->_table->db()->query('SELECT * FROM test');

			$row = $res->fetch_assoc();

			$this->assertEquals($row['id'], 2);
			$this->assertEquals($row['first_name'], 'Igor');
			$this->assertEquals($row['last_name'], 'Vorobioff');
			$this->assertEquals($row['number'], 1);
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testInsertAll()
	{
		try
		{
			$data = array(
				array(
					'id' => 1,
					'first_name' => 'Igor',
					'last_name' => 'Vorobioff',
					'number' => 1
				),
				array(
					'id' => 2,
					'first_name' => 'Igor2',
					'last_name' => 'Vorobioff2',
					'number' => 1
				)
			);

			$this->_table->insertAll($data);

			$res = $this->_table->db()->query('SELECT * FROM test');

			$row1 = $res->fetch_assoc();
			$row2 = $res->fetch_assoc();

			$this->assertTrue($row1['id'] + 1 == $row2['id']);
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testFetchOne()
	{
		try
		{
			$data = array(
				array(
					'id' => 1,
					'first_name' => 'Igor',
					'last_name' => 'Vorobioff',
					'number' => 1
				),
				array(
					'id' => 2,
					'first_name' => 'Igor2',
					'last_name' => 'Vorobioff2',
					'number' => 1
				)
			);

			$this->_table->insertAll($data);

			$res = $this->_table->select('id')->select('first_name')->fetchOne('id', 2);

			$this->assertTrue($res['id'] == 2);
			$this->assertTrue(count($res) == 2);
			$this->assertTrue(isset($res['first_name']));
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testOrderBy()
	{
		try
		{
			$data = array(
				array(
					'id' => 1,
					'first_name' => 'Igor',
					'last_name' => 'Vorobioff',
					'number' => 1
				),
				array(
					'id' => 2,
					'first_name' => 'Igor2',
					'last_name' => 'Vorobioff2',
					'number' => 1
				)
			);

			$this->_table->insertAll($data);

			$res = $this->_table
				->select('id')
				->orderBy('id')
				->fetchOne();

			$this->assertTrue($res['id'] == 2);
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testGroupBy()
	{
		try
		{
			$data = array(
				array(
					'id' => 1,
					'first_name' => 'Igor',
					'last_name' => 'Vorobioff',
					'number' =>123
				),
				array(
					'id' => 2,
					'first_name' => 'Igor',
					'last_name' => 'Vorobioff',
					'number' => 34
				)
			);

			$this->_table->insertAll($data);

			$res = $this->_table
				->select('first_name, last_name')
				->groupBy('last_name')
				->fetchAll();

			$this->assertTrue(count($res) == 1, 'Must be one row.');
			$this->assertTrue($res[0]['first_name'] == 'Igor');
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testLeftJoin()
	{
		try
		{
			$this->_table->insert(array('id' => 1, 'first_name' => 'Igor', 'last_name' => 'Vorobioff'));
			$this->_table2->insert(array('id' => 1, 'dob' => '2011-08-09 12:12:01'));

			$this->_table2->setAlias('t2');

			$row = $this->_table
				->setAlias('t1')
				->select('t2.dob, t1.id')
				->join($this->_table2, 't1.id = t2.id')
				->fetchOne();

			$this->assertTrue($row['dob'] == '2011-08-09 12:12:01');
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}
}