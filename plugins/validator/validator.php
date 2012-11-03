<?php
namespace Plugins\Validator;
use \Plugins\Validator\Lib\MainRule;
use \Plugins\Validator\Lib\Exception as RuleException;
use \Plugins\Validator\Rules\IsDefined;

/**
 * Класс валидатор.
 * Предназначен для валидации данных.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Validator
{
	private $_errors = array();

	private $_strict_mod;

	private $_config = array(
		'strict_mode' => true,
		'single_violation' => true,
	);

	static private $_instance = null;

	static public function isValid($data, array $rules_map, $config = array())
	{
		if (self::$_instance == null)
		{
			self::$_instance = new self();
		}

		self::$_instance->_config = array_merge(self::$_instance->_config, $config);

		foreach ($rules_map as $field_name => $rules)
		{
			self::$_instance->_applyRules($field_name, $data,  $rules);
		}

		return self::$_instance->_errors ? false : true;
	}


	static public function fetchErrors()
	{
		$erros = self::$_instance->_errors;

		 self::$_instance->_errors = array();

		return $erros;
	}

	private function _applyRules($field, $data, $rules)
	{
		if ($this->_config['strict_mode'])
		{
			if (!$this->_isDefined($field, $data))
			{
				return ;
			}
		}

		if (!is_array($rules))
		{
			$rules = array($rules);
		}

		foreach ($rules as $rule)
		{
			try
			{
				$rule->validate($data[$field], $field, $data);
			}
			catch (RuleException $ex)
			{
				$this->_errors[] = $ex->getError();

				if ($this->_config['single_violation'])
				{
					return ;
				}
			}
		}
	}

	private function _isDefined($field, $data)
	{
		try
		{
			$is_defined = new IsDefined();
			$is_defined->validate(null, $field, $data);
		}
		catch(RuleException $ex)
		{
			$this->_errors[] = $ex->getError();
			return false;
		}

		return true;
	}
}