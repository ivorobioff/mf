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
	protected $_tableName;
	protected $_tableAlias = '';
	protected $_orderBy = 'id ASC';

	private $_initQuery = array(
		'select' => array(),
		'where' => array(),
		'whereIn' => array(),
		'orderBy' => array(),
		'groupBy' => array(),
		'duplicate' => '',
		'limit' => '',
		'join' => array()
	);

	private $_queryBuffer = array();

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

		$this->clear();
	}

	/**
	 * $table->duplicate('c=c+1');
	 * $table->duplicate('c=c+', 1);
	 * $table->duplicate('c', 1);
	 */
	public function duplicate($q = '', $value = null)
	{
		if (is_null($value))
		{
			$this->_queryBuffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q;
			return $this;
		}

		$eq = strpos($q, '=') ? '' : '=';

		$this->_queryBuffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q.$eq.'\''.$this->escape($value).'\'';
		return $this;
	}

	/**
	 * $table->select('col1, col2, col3');
	 */
	public function select($q = '')
	{
		$this->_queryBuffer['select'][] = $q;
		return $this;
	}

	/**
	 * $table->where('col1', 10);
	 * $table->where('col1 = 10');
	 * $table->where('col1!=', '10');
	 * $table->where('col1 LIKE', 'test');
	 */
	public function where($q, $value = null)
	{
		if (is_null($value))
		{
			$this->_queryBuffer['where'][] = 'AND '.$q;
			return $this;
		}

		if (strpos(strtolower($q), 'like'))
		{
			$this->_queryBuffer['where'][] = 'AND '.$q.' LIKE \'%'.$this->escape($value).'%\'';
			return $this;
		}

		$eq = strpos($q, '=') ? '' : '=';

		$this->_queryBuffer['where'][] = 'AND '.$q.$eq.'\''.$this->escape($value).'\'';

		return $this;
	}

	private function clear()
	{
		$this->_queryBuffer = $this->_initQuery;
	}

	public function db()
	{
		return self::$_db;
	}

	public function setAlias($alias)
	{
		$this->_tableAlias = $alias;
		return $this;
	}

	public function getAlias()
	{
		return $this->_tableAlias;
	}

	public function getTableName()
	{
		return $this->_tableName;
	}

	public function escape($str)
	{
		return self::$_db->escape_string($str);
	}

	public function whereIn()
	{

	}

	public function limit($start, $offset = null)
	{
		$this->_queryBuffer['limit'] = 'LIMIT '.$start;

		if (is_int($offset))
		{
			$this->_queryBuffer['limit'].', '.$offset;
		}

		return $this;
	}

	public function orderBy($field, $direction = 'DESC')
	{
		$this->_queryBuffer['orderBy'][] = '`'.$field.'` '.$direction;

		return $this;
	}

	public function groupBy($field)
	{
		$this->_queryBuffer['groupBy'][] = '`'.$field.'`';
		return $this;
	}

	public function join(\System\Db\ActiveRecord $table, $cond, $type = 'LEFT JOIN')
	{
		$alias = $table->getAlias() ? 'AS '.$table->getAlias() : '';

		$this->_queryBuffer['join'][] = $type.' '.$table->getTableName().' '.$alias.' ON '.$cond;

		return $this;
	}

	public function update()
	{

	}

	public function updateAll()
	{

	}

	public function insert(array $data)
	{
		$sql = 'INSERT INTO '.$this->_tableName.' ('.$this->_prepareKeys($data).')
				VALUES('.$this->_prepareValues($data).') '.$this->_queryBuffer['duplicate'];

		$res = $this->query($sql);

		$this->clear();

		return $res ? self::$_db->insert_id : false;
	}

	public function insertAll(array $data)
	{
		$values = '';
		$d = '';

		foreach ($data as $row)
		{
			$values .= $d.'('.$this->_prepareValues($row).')';
			$d = ',';
		}

		$sql = 'INSERT INTO '.$this->_tableName.' ('.$this->_prepareKeys($data[0]).')
				VALUES'.$values.' '.$this->_queryBuffer['duplicate'];

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
	}

	public function delete()
	{

	}

	public function fetchOne($key = null, $value = null)
	{
		$this->limit(1);

		$res = $this->_fetch($key, $value);

		return $res ? $res[0] : array();
	}

	public function fetchAll($key = null, $value = null)
	{
		return $this->_fetch($key, $value);
	}

	private function _fetch($key = null, $value = null)
	{
		if (!is_null($key))
		{
			$this->where($key, $value);
		}

		$sql = 'SELECT '.$this->_prepareSelects().
			' FROM '.$this->_tableName.
			' '.$this->_prepareAlias().
			' '.implode(' ', $this->_queryBuffer['join']).
			' WHERE 1=1 '.$this->_prepareWheres().
			' '.$this->_prepareGroupBys().
			' '.$this->_prepareOrderBys().
			' '.$this->_queryBuffer['limit'];

		$res = $this->_select($sql);

		$this->clear();

		return $res;
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

	private function _prepareAlias()
	{
		if (!$this->_tableAlias)
		{
			return  '';
		}

		return 'AS '.$this->_tableAlias;
	}

	private function _prepareGroupBys()
	{
		if (!$this->_queryBuffer['groupBy'])
		{
			return '';
		}

		return 'GROUP BY '.implode(',', $this->_queryBuffer['groupBy']);
	}

	private function _prepareOrderBys()
	{
		if (!$this->_queryBuffer['orderBy'])
		{
			return	'ORDER BY '.$this->_orderBy;
		}

		return 'ORDER BY '.implode(',', $this->_queryBuffer['orderBy']);
	}
	private function _prepareSelects()
	{
		if (!$this->_queryBuffer['select'])
		{
			return '*';
		}

		return implode(',', $this->_queryBuffer['select']);
	}

	private function _prepareWheres()
	{
		$wheres = '';

		foreach ($this->_queryBuffer['where'] as $value)
		{
			$wheres .= ' '.$value;
		}

		return $wheres;
	}

	private function _prepareValues(array $data)
	{
		$d = '';
		$values = '';

		foreach ($data as $value)
		{
			$values .= $d.'\''.$value.'\'';
			$d = ',';
		}

		return $values;
	}

	private function _prepareKeys(array $data)
	{
		$d = '';
		$keys = '';

		foreach ($data as $key => $value)
		{
			$keys .= $d.'`'.$key.'`';
			$d = ',';
		}

		return $keys;
	}
}