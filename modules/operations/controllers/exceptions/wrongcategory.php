<?php
namespace Controller\Operations\Exceptions;

class WrongCategory extends \System\Exceptions\Controller
{
	protected function _handler(array $gets, array $posts)
	{
		echo 'Wrong category';
	}
}