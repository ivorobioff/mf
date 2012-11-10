<?php
namespace Lib\Logs\Results;

use \Plugins\Utils\SmartArray;

class Row extends SmartArray
{
	private $_operations_dictionary = array(
		'budget_withdrawal' => 'Снятие с бюджета',
		'budget_deposit' => 'Внос в бюджета',
		'category_withdrawal' => 'Снятие с категории',
		'return_amount' => 'Возврат суммы',
		'request_amount' => 'Запрос суммы',
		'change_amount' => 'Изменение суммы',
		'delete_category' => 'Удаление категории',
		'create_category' => 'Создание категории'
	);

	public function offsetGet($offset)
	{
		if ($offset == 'fixation')
		{
			return unserialize($this->_data[$offset]);
		}

		if ($offset == 'operation')
		{
			return $this->_translateOperation($this->_data[$offset]);
		}

		return $this->_data[$offset];
	}

	private function _translateOperation($alias)
	{
		return always_set($this->_operations_dictionary, $alias, $alias);
	}
}