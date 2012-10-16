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

				->source('ns.js')
				->source('sys/class.js')
				->source('views/views.abstract.context_menu.js')
				->source('views/views.category_cmenu.js')
				->source('views/views.group_cmenu.js')
				->source('views/views.ptable.js')
				->source('handlers/handlers.abstract.category.js')
				->source('handlers/handlers.category.js')
				->source('handlers/handlers.group.js')

				->destination(Server::get('document_root').'/front/js/app.js')
				->process();
		}
		catch (Exception $ex)
		{
			die($ex->getMessage());
		}
	}
}