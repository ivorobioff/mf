<?php
namespace System\Db;

class ActiveRecord
{
	private $_config_db = array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '1234',
		'dbname' => 'mf'
	);

	protected $_displayErrors = true;

	/**
	 * Настройки таблицы
	 */

	protected $_name;
	protected $_alias = '';

	static private $_db;

	public function __construct()
	{
		if (!self::$_db)
		{
			self::$_db = new \mysqli(
				$this->_config_db['host'],
				$this->_config_db['username'],
				$this->_config_db['password'],
				$this->_config_db['dbname']
			);
		}
	}

	public function db()
	{
		return self::$_db;
	}

	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	public function escape($str)
	{
		return self::$_db->escape_string($str);
	}

	public function select()
	{

	}

	public function where()
	{

	}

	public function whereIn()
	{

	}

	public function limit()
	{

	}

	public function join()
	{

	}

	public function update()
	{

	}

	public function uspdateAll()
	{

	}

	public function insert($data)
	{
		$d = '';
		$keys = '';
		$values = '';

		foreach ($data as $key => $value)
		{
			$keys .= $d.$key;
			$values .=$d.'\''.$this->escape($value).'\'';
			$d = ',';
		}

		$sql = 'INSERT INTO '.$this->_name.' ('.$keys.') VALUES('.$values.')';
		$res = $this->query($sql);

		return $res ? self::$_db->insert_id : false;
	}

	public function insertAll()
	{

	}

	public function delete()
	{

	}

	public function fetchOne()
	{

	}

	public function fetchAll()
	{

	}

	public function query($sql)
	{
		if(!$res = self::$_db->query($sql))
		{
			if($this->_displayErrors)
			{
				die(self::$_db->error);
			}

			return false;
		}

		return $res;
	}

	private function _select($sql)
	{
		$data = array();

		$res = $this->query($sql);

		while($row = $res->fetch_assoc())
		{
			$data[]=$row;
		};

		return $data;
	}
}