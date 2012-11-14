<?php
namespace Controller\Qunit;

use \Plugins\Minimizer\Minimizer;
use \Plugins\Minimizer\Exception as MinException;

class Run extends \System\Mvc\Controller
{
	protected $_default_layout = 'qunit/index.phtml';

	public function url()
	{
		$this->_render('qunit/tests/lib.url.phtml');
	}

	protected function _initPage()
	{
		$minimize = new Minimizer('/front/min_config.xml');

		try
		{
			$minimize->process(false);
		}
		catch (MinException $ex)
		{
			die($ex->getMessage());
		}
	}
}