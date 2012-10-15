<?php
namespace System\Lib;

use \Plugins\Minimizer\Minimizer;
use \System\Lib\Server;
use \Plugins\Minimizer\Exception;

/**
 * Рутер создает и запускает run данного объекта перед созданием контроллера
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Boot
{
	public function run()
	{
		$minimize = new Minimizer();

		try
		{
			$minimize
				->basePath(Server::get('document_root').'/front/js/app')

				->source('lib/class.js')
				->source('views/context_menu.js')
				->source('views/ptable_view.js')
				->source('handlers/abs_category_handler.js')
				->source('handlers/category_handler.js')
				->source('handlers/group_handler.js')

				->destination(Server::get('document_root').'/front/js/app.js')
				->process();
		}
		catch (Exception $ex)
		{
			die($ex->getMessage());
		}
	}
}