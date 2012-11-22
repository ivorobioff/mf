<?php
namespace Model\Common;

abstract class Archivable
{
	/**
	 * @return array
	 */
	abstract public function prepareData();

	abstract public function getToken();

	public function onSave()
	{

	}
}