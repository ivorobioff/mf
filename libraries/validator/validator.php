<?php
namespace Common\Lib\Validator;
use \Common\Lib\Validator\Lib\MainRule;
use \Common\Lib\Validator\Lib\Exception as RuleException;

/**
 * Класс валидатор.
 * Предназначен для валидации данных.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Validator
{
	private $_rules = array();
	private $_errors = array();

	public function setRule(MainRule $rule)
	{
		$this->_rules[] = $rule;
	}

	public function isValid($param)
	{
		foreach ($this->_rules as $rule)
		{
			try
			{
				$rule->validate($param);
			}
			catch (RuleException $ex)
			{
				$this->_errors[] = $ex->getError();
			}
		}

		$this->clearRules();

		return $this->_errors ? false : true;
	}

	public function fetchErrors()
	{
		$erros = $this->_errors;

		$this->_errors = array();

		return $erros;
	}

	public function clearRules()
	{
		$this->_rules = array();
	}
}