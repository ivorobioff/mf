<?php
namespace Lib\Common;

class Utils
{
	static function toMoney($number)
	{
		return sprintf('%0.2f', $number);
	}
}