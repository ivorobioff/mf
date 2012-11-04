<?php
namespace System\Mvc;

use \System\Db\ActiveRecord;
use \System\Mvc\SmartModel;

abstract class Model
{
	protected $_id_key = 'id';

	protected $_id;

	function __construct($id = null)
	{
		$this->_id = $id;
	}

	/**
	 * Основная таблица
	 * @var ActiveRecord
	 */
	protected $_table;

	public function getAll()
	{
		return $this->_table->fetchAll();
	}

	/**
	 * @param int $id
	 * @return RowModel
	 */
	public function getRowModel($id = null)
	{
		if (is_null($id) && !is_null($this->_id))
		{
			$id = $this->_id;
		}

		return new RowModel($id, array(
			'id_value' => $id,
			'id_key' => $this->_id_key,
			'base_table' => $this->_table
		));
	}
}