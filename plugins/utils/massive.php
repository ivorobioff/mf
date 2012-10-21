<?php
namespace Plugins\Utils;
/**
 * Утилитки для работы с массивом
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Massive
{
	/**
	 * Применяет правила для каждого поля массива.
	 * @param mixed $rules
	 * @param array $data
	 */
	static public function applyRules(array &$data, $rules)
	{
		if (!is_array($rules))
		{
			foreach ($data as $k => $v)
			{
				if (is_callable($rules))
				{
					$data[$k] = $rules($data[$k]);
				}
			}

			return ;
		}

		foreach ($rules as $field_name => $rule)
		{
			if (!isset($data[$field_name]))
			{
				continue;
			}

			if (!is_callable($rule))
			{
				continue;
			}

			$data[$field_name] = $rule($data[$field_name]);
		}
	}
}