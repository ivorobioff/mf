<?php
namespace Controller\Operations\Helpers;

use \Plugins\Validator\Rules\Emptiness;

class Planner
{
	static public function getCategoryMassiveRules()
	{
		return array(
			'title' =>  function($value) { return trim($value); },
			'amount' => function ($value) { return sprintf('%0.2f', floatval($value)); },
		);
	}

	static public function getCategoryValidatorRules()
	{
		return array(
			'title' => new Emptiness('Поле "Название" не должно быть пустым.')
		);
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
}