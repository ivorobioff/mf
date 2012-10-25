<?php
namespace System\Db;

abstract class ActiveRecord
{
	private $_config_db = array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '1234',
		'dbname' => 'mf'
	);

	protected $_display_errors = true;

	/**
	 * Настройки таблицы
	 */
	protected $_table_name;
	protected $_table_alias = '';
	protected $_order_by = 'id ASC';

	/**
	 * Прототип буфера запросов
	 * @var array
	 */
	private $_init_query = array(
		'select' => array(),
		'where' => array(),
		'orderBy' => array(),
		'groupBy' => array(),
		'duplicate' => '',
		'limit' => '',
		'join' => array()
	);

	private $_query_buffer = array();

	private $_last_query = '';

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

		self::$_db->set_charset('utf8');

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
			$this->_query_buffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q;
			return $this;
		}

		$eq = strpos($q, '=') ? '' : '=';

		$this->_query_buffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q.$eq.'\''.$this->escape($value).'\'';
		return $this;
	}

	/**
	 * $table->select('col1, col2, col3');
	 */
	public function select($q = '')
	{
		$this->_query_buffer['select'][] = $q;
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
		$ch = array('like', '=', '>', '<');

		if (is_array($value))
		{
			$this->_query_buffer['where'][] = $type.' '.$q.' IN ('.$this->_prepareValues($value).')';
			return $this;
		}

		if (is_null($value))
		{
			$this->_query_buffer['where'][] = $type.' '.$q;
			return $this;
		}

		$eq = $this->_getSignsCond($q)  ? '' : '=';

		$this->_query_buffer['where'][] = $type.' '.$q.$eq.'\''.$this->escape($value).'\'';
	}

	private function _getSignsCond($q)
	{
		return strpos($q, '=')
			|| strpos(strtolower($q), 'like')
			|| strpos($q, '>')
			|| strpos($q, '<');
	}

	public function clear()
	{
		$this->_query_buffer = $this->_init_query;
	}

	public function db()
	{
		return self::$_db;
	}

	public function setAlias($alias)
	{
		$this->_table_alias = $alias;
		return $this;
	}

	public function getAlias()
	{
		return $this->_table_alias;
	}

	public function prepareAlias()
	{
		return  $this->_table_alias ? 'AS '.$this->_table_alias : '';
	}

	public function getTableName()
	{
		return $this->_table_name;
	}

	public function escape($str)
	{
		return self::$_db->escape_string($str);
	}

	public function limit($start, $offset = null)
	{
		$this->_query_buffer['limit'] = 'LIMIT '.$start;

		if (is_int($offset))
		{
			$this->_query_buffer['limit'].', '.$offset;
		}

		return $this;
	}

	public function orderBy($field, $direction = 'DESC')
	{
		$this->_query_buffer['orderBy'][] = $field.' '.$direction;

		return $this;
	}

	public function groupBy($field)
	{
		$this->_query_buffer['groupBy'][] = $field;
		return $this;
	}

	public function join(\System\Db\ActiveRecord $table, $cond, $type = 'LEFT JOIN')
	{
		$this->_query_buffer['join'][] = $type.' '.$table->getTableName().' '.$table->prepareAlias().' ON '.$cond;

		return $this;
	}

	/**
	 * $table->update('c=c+2');
	 * $this->update(array('c', 2));
	 * @return int
	 */
	public function update($data)
	{
		if (!$data)
		{
			return false;
		}

		$sql = 'UPDATE '.$this->_table_name.
			' SET '.$this->_prepareUpdates($data).
			' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
	}

	public function insert(array $data)
	{
		$sql = 'INSERT INTO '.$this->_table_name.' ('.$this->_prepareKeys($data).')
				VALUES('.$this->_prepareValues($data).') '.$this->_query_buffer['duplicate'];

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

		$sql = 'INSERT INTO '.$this->_table_name.' ('.$this->_prepareKeys($data[0]).')
				VALUES'.$values.' '.$this->_query_buffer['duplicate'];

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

		$sql = 'DELETE FROM '.$this->_table_name.' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();
	}

	public function getValue($key, $default = false)
	{
		$res = $this->fetchOne();

		return always_set($res, $key, $default);
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
			' FROM '.$this->_table_name.' '.$this->prepareAlias().
			' '.$this->_prepareJoins().
			' '.$this->_prepareWheres().
			' '.$this->_prepareGroupBys().
			' '.$this->_prepareOrderBys().
			' '.$this->_query_buffer['limit'];

		$res = $this->_select($sql);

		$this->clear();

		return $res;
	}

	public function getLastQuery()
	{
		return $this->_last_query;
	}

	public function query($sql)
	{
		$this->_last_query = $sql;

		if(!$res = self::$_db->query($sql))
		{
			if($this->_display_errors)
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

	private function _prepareUpdates($data)
	{
		if (is_string($data))
		{
			return $data;
		}

		$updates = '';
		$d = '';

		foreach ($data as $k => $v)
		{
			$eq = strpos($k, '=') ? '' : '=';

			$updates .=$d.$k.$eq.'\''.$this->escape($v).'\'';
			$d = ',';

			$eq = '';
		}

		return $updates;
	}

	private function _prepareJoins()
	{
		return implode(' ', $this->_query_buffer['join']);
	}

	private function _prepareGroupBys()
	{
		if (!$this->_query_buffer['groupBy'])
		{
			return '';
		}

		return 'GROUP BY '.implode(',', $this->_query_buffer['groupBy']);
	}

	private function _prepareOrderBys()
	{
		if (!$this->_query_buffer['orderBy'])
		{
			return	'ORDER BY '.$this->_order_by;
		}

		return 'ORDER BY '.implode(',', $this->_query_buffer['orderBy']);
	}
	private function _prepareSelects()
	{
		if (!$this->_query_buffer['select'])
		{
			return '*';
		}

		return implode(',', $this->_query_buffer['select']);
	}

	private function _prepareWheres()
	{
		$wheres = '1=1';

		foreach ($this->_query_buffer['where'] as $value)
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