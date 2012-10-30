<?php
namespace System\Db;

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

	public function __construct($_id = null)
	{
		$this->_id = $_id;
	}

	public function __call($method, $arguments)
	{
		if (isset($this->_id))
		{
			if (substr($method, 0, 3) == 'get')
			{
				$field = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', substr($method, 3)));

				return $this->_table
					->where($this->_id_key, $this->_id)
					->select($field)
					->getValue($field, always_set($arguments, 0));
			}

			if (substr($method, 0, 3) == 'set')
			{
				$field = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', substr($method, 3)));

				return $this->_table
					->where($this->_id_key, $this->_id)
					->update($field, $arguments[0]);
			}
		}
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

	public function setId($id)
	{
		$this->_id = $id;
	}
}