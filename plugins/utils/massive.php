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
	static public function applyRules($rules, array &$data)
	{
		foreach ($data as $k => $v)
		{
			if (is_array($v))
			{
				self::applyRules($rules, $data[$k]);

				continue;
			}

			$rule = $rules;

			if (is_array($rule))
			{
				$rule = always_set($rules, $k);
			}

			if (!$rule)
			{
				continue;
			}

			if (!is_callable($rule))
			{
				continue;
			}

			$data[$k] = $rule($v);
 		}
	}
}