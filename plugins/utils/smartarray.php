<?php
namespace Plugins\Utils;

abstract class SmartArray implements \ArrayAccess, \Iterator
{
	protected $_data = array();

	public function __construct(array $data)
	{
		$this->_data = $data;
	}

	/**
	 * @param offset
	 */
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	/**
	 * @param offset
	 */
	public function offsetGet($offset)
	{
		return $this->_data[$offset];
	}

	/**
	 * @param offset
	 * @param value
	 */
	public function offsetSet($offset, $value)
	{
		$this->_data[$offset] = $value;
	}

	/**
	 * @param offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->_data['offset']);
	}

	public function current()
	{
		return $this->offsetGet($this->key());
	}

	public function next()
	{
		next($this->_data);
	}

	public function key()
	{
		return key($this->_data);
	}

	public function valid()
	{
		return $this->offsetExists($this->key());
	}

	public function rewind()
	{
		reset($this->_data);
	}

	public function toArray()
	{
		$res = array();

		foreach ($this as $key => $value)
		{
			$res[$key] = $value;
		}

		return $res;
	}
}