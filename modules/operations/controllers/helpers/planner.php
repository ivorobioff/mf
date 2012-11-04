<?php
namespace Controller\Operations\Helpers;

use Plugins\Validator\Rules\Numeric;
use \Plugins\Validator\Rules\Emptiness;
use \Model\Operations\Categories as ModelCategories;

class Planner
{
	static public function getCategoryMassiveRules()
	{
		return array(
			'title' =>  function($value) { return trim($value); },
			'amount' => function ($value) { return sprintf('%0.2f', floatval($value)); }
		);
	}

	static public function getCategoryValidatorRules()
	{
		return array(
			'title' => new Emptiness('Поле "Название" не должно быть пустым.'),
			'amount' => new Numeric('Сумма должна быть числовым значением.'),
		);
	}

	static public function getCategoryValidatorRulesWithId()
	{
		return array_merge(array('id' => true), self::getCategoryValidatorRules());
	}

	static public function getGroupMassiveRules()
	{
		return array(
			'name' =>  function($value) { return trim($value); },
		);
	}

	static public function getGroupValidatorRules()
	{
		return array(
			'name' => new Emptiness('Поле "Название" не должно быть пустым.')
		);
	}

	static public function getNewCurrentAmount(array $data)
	{
		$cat = new ModelCategories($data['id']);

		$info = $cat->get();

		return $info['current_amount'] + ($data['amount'] - $info['amount']);
	}
}