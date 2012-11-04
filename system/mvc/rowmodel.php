<?php
namespace System\Mvc;

use \System\Db\ActiveRecord;
use \System\Mvc\RowException;

class RowModel
{
	protected $_id;

	protected $_id_key;

	/**
	 * Базовый источник данных для модели
	 * @var ActiveRecord
	 */
	protected $_base_table;

	protected $_data = array();

	public function __construct($id, array $config)
	{
		$this->_id = $config['id_value'];
		$this->_id_key = $config['id_key'];
		$this->_base_table = $config['base_table'];

		$this->_data = $this->_base_table->fetchOne($this->_id_key, $this->_id);

		if (!$this->_data)
		{
			throw new RowException('There is no data for id "'.$id.'"');
		}
	}

	public function get()
	{
		return $this->_data;
	}

	public function __get($method, $arguments)
	{
		$field = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', substr($method, 3)));

		if (!isset($this->_data[$field]))
		{
			throw new RowException('Cannot find the field "'.$field.'"');
		}

		if (substr($method, 0, 3) == 'get')
		{
			return $this->_data[$field];
		}

		if (substr($method, 0, 3) == 'set')
		{
			if (!isset($arguments[0]))
			{
				return ;
			}

			$this->_data[$field] = $arguments[0];
			return $this;
		}
	}

	public function save()
	{
		return $this->_base_table
			->where($this->_id_key, $this->_id)
			->update($this->_data);
	}
}