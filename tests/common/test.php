<?php
namespace Test\Common;

class Test extends \PHPUnit_Framework_TestCase
{
	private $_table;
	private $_table2;

	public function setUp()
	{
		try
		{
			$this->_table = new \Test\Common\Db\Test();
			$this->_table2 = new \Test\Common\Db\Test2();
		}
		catch (\Exception $ex)
		{
			pred($ex->getMessage());
		}

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

	public function testLike()
	{
		try
		{
			$data = array(
				array(
					'id' => 1,
					'first_name' => 'Igor',
					'last_name' => 'Vorobiov',
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
				->select('last_name')
				->where('last_name LIKE', '%ioff')
				->fetchOne();

			$this->assertTrue($res['last_name'] == 'Vorobioff');
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testIn()
	{
		try
		{
			$data = array();

			for ($i = 0; $i <= 10; $i++)
			{
				$data[] = array(
					'id' => $i,
					'first_name' => 'Igor',
					'last_name' => 'Vorobiov',
					'number' =>123
				);
			}

			$this->_table->insertAll($data);

			$res = $this->_table->fetchAll('id', array(3, 9));

			foreach ($res as $v)
			{
				$this->assertTrue(in_array($v['id'], array(3, 9)));
			}
		}
		catch(\Exception $er)
		{
			die($er->getMessage());
		}
	}

	public function testUpdate()
	{
		$this->_table->insert(
			array(
				'id' => 1,
				'first_name' => 'Igor',
				'last_name' => 'Vorobiov',
				'number' =>123
			)
		);

		$this->_table->insert(
			array(
				'id' => 2,
				'first_name' => 'Igor',
				'last_name' => 'Vorobiov',
				'number' =>123
			)
		);

		$ar = $this->_table->where('id', 2)->update(array('last_name' => 'Vorobio\'ff'));

		$this->assertTrue($ar == 1);

		$res = $this->_table->fetchAll();

		$this->assertTrue($res[0]['last_name'] == 'Vorobiov' && $res[0]['id'] == 1, 'n1');
		$this->assertTrue($res[1]['last_name'] == 'Vorobio\'ff' && $res[1]['id'] == 2, 'n2');
	}

	public function testDelete()
	{
		$data = array();

		for ($i = 0; $i <= 10; $i++)
		{
			$data[] = array(
				'id' => $i,
				'first_name' => 'Igor',
				'last_name' => 'Vorobiov',
				'number' =>123
			);
		}

		$this->_table->insertAll($data);

		$this->_table
			->where('id', 2)
			->either('id', 5)
			->either('id', 8)
			->delete();

		$res = $this->_table->fetchAll('id', array(2, 5, 8));

		$this->assertFalse((bool) $res);
	}
}