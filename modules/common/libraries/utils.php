<?php
namespace Lib\Common;

class Utils
{
	/**
	 * Переводит любое число в денежный формат
	 * @param number $number
	 */
	static function toMoney($number)
	{
		return sprintf('%0.2f', $number);
	}
}