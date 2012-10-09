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

	/**
	 * Прототип буфера запросов
	 * @var array
	 */
	private $_initQuery = array(
		'select' => array(),
		'where' => array(),
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

	public function either($q, $value = null)
	{
		$this->_where('OR', $q, $value);
		return $this;
	}

	public function where($q, $value = null)
	{
		$this->_where('AND', $q, $value);
		return $this;
	}

	/**
	 * $table->where('col1', 10);
	 * $table->where('col1 = 10');
	 * $table->where('col1!=', '10');
	 * $table->where('col1', array(1, 2, 4));
	 */
	private function _where($type, $q, $value = null)
	{
		if (is_array($value))
		{
			$this->_queryBuffer['where'][] = $type.' '.$q.' IN ('.$this->_prepareValues($value).')';
			return $this;
		}

		if (is_null($value))
		{
			$this->_queryBuffer['where'][] = $type.' '.$q;
			return $this;
		}

		$eq = strpos($q, '=') || strpos(strtolower($q), 'like') ? '' : '=';

		$this->_queryBuffer['where'][] = $type.' '.$q.$eq.'\''.$this->escape($value).'\'';
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
		$this->_tableAlias = 'AS '.$alias;
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
		$this->_queryBuffer['orderBy'][] = $field.' '.$direction;

		return $this;
	}

	public function groupBy($field)
	{
		$this->_queryBuffer['groupBy'][] = $field;
		return $this;
	}

	public function join(\System\Db\ActiveRecord $table, $cond, $type = 'LEFT JOIN')
	{
		$this->_queryBuffer['join'][] = $type.' '.$table->getTableName().' '.$table->getAlias().' ON '.$cond;

		return $this;
	}

	public function update(array $data)
	{
		if (!$data)
		{
			return false;
		}

		$sql = 'UPDATE '.$this->_tableName.
			' SET '.$this->_prepareUpdates($data).
			' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
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

	public function delete($q = null, $value = null)
	{
		if (!is_null($q))
		{
			$this->where($q, $value);
		}

		$sql = 'DELETE FROM '.$this->_tableName.' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();
	}

	public function fetchOne($key = null, $value = null)
	{
		$res = $this->limit(1)->_fetch($key, $value);

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
			' FROM '.$this->_tableName.' '.$this->_tableAlias.
			' '.$this->_prepareJoins().
			' '.$this->_prepareWheres().
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

	private function _prepareUpdates(array $data)
	{
		$updates = '';
		$d = '';

		foreach ($data as $k => $v)
		{
			$updates .=$d.$k.'=\''.$this->escape($v).'\'';
			$d = ',';
		}

		return $updates;
	}

	private function _prepareJoins()
	{
		return implode(' ', $this->_queryBuffer['join']);
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
		$wheres = '1=1';

		foreach ($this->_queryBuffer['where'] as $value)
		{
			$wheres .= ' '.$value;
		}

		return 'WHERE '.$wheres;
	}

	private function _prepareValues(array $data)
	{
		$d = '';
		$values = '';

		foreach ($data as $value)
		{
			$values .= $d.'\''.$this->escape($value).'\'';
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
			$keys .= $d.$key;
			$d = ',';
		}

		return $keys;
	}
}