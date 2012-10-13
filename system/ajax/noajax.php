<?php
namespace System\Ajax;

/**
 * Контроллер которому будет передан контроль в исключительной ситуацие
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Noajax extends \System\Mvc\Controller
{
	public function handler()
	{
		echo 'Only AJAX is allowed';
	}
}