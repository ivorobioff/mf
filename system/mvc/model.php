<?php
namespace System\Mvc;

use System\Exceptions\Exception;
use \System\Db\ActiveRecord;

abstract class Model
{
	protected $_id;

	protected $_id_key = 'id';

	/**
	 * Основная таблица
	 * @var ActiveRecord
	 */
	protected $_table;

	public function __construct($id = null)
	{
		$this->_id = $id;

		$this->_table = $this->_getTable();
	}

	public function __call($method, $arguments)
	{
		if (isset($this->_id))
		{
			$field = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', substr($method, 3)));

			if (substr($method, 0, 3) == 'get')
			{
				return $this->_table
					->where($this->_id_key, $this->_id)
					->select($field)
					->getValue($field);
			}

			if (substr($method, 0, 3) == 'set')
			{
				return $this->_table
					->where($this->_id_key, $this->_id)
					->update($field, $arguments[0]);
			}
		}

		throw new Exception('Call undefined method "'.$method.'"');
	}

	public function get()
	{
		return $this->_table
			->where($this->_id_key, $this->_id)
			->fetchOne();
	}

	public function getAll()
	{
		return $this->_table->fetchAll();
	}

	/**
	 * @return ActiveRecord
	 */
	abstract protected function _getTable();
}