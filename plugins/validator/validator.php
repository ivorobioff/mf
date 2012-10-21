<?php
namespace Plugins\Validator;
use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception as RuleException;

/**
 * Класс валидатор.
 * Предназначен для валидации данных.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Validator
{
	private $_errors = array();

	public function isValid($data, array $rules_map)
	{
		foreach ($rules_map as $field_name => $rules)
		{
			if (!isset($data[$field_name]))
			{
				continue;
			}

			$this->_applyRules($data[$field_name], $rules);
		}

		return $this->_errors ? false : true;
	}


	public function fetchErrors()
	{
		$erros = $this->_errors;

		$this->_errors = array();

		return $erros;
	}

	private function _applyRules($value, $rules)
	{
		if (!is_array($rules))
		{
			$rules = array($rules);
		}

		foreach ($rules as $rule)
		{
			try
			{
				$rule->validate($value);
			}
			catch (RuleException $ex)
			{
				$this->_errors[] = $ex->getError();
			}
		}
	}
}