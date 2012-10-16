<?php
namespace Plugins\Minimizer;

use \Plugins\Minimizer\JSMin;
use \Plugins\Minimizer\JSMinException;
use \Plugins\Minimizer\Exception;
use \System\Lib\Server;
/**
 * Класс для объединения и минимизации всех js добавленых в стэк.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Minimizer
{
	private $_xml;

	private $_output = '';

	public function __construct($xml)
	{
		$this->_loadXML(trim($xml, '/'));
	}


	public function process()
	{
		$js_min = '';

		$files = $this->_getFiles();

		$this->_output = Server::get('document_root').$this->_xml->load->attributes()->output;

		foreach ($files as $file)
		{
			$file = Server::get('document_root').$file;

			if (!file_exists($file))
			{
				throw new Exception('Cannot load the script file: '.$file);
			}

			try
			{
				$js_min .= JSMin::minify(file_get_contents($file));
			}
			catch (JSMinException $ex)
			{
				throw new Exception($ex->getMessage());
			}
		}

		$this->_saveJS($js_min);
	}

	public function getScriptTags()
	{
		$tags = '';

		$files = $this->_getFiles();

		foreach ($files as $file)
		{
			if (!file_exists(Server::get('document_root').$file))
			{
				throw new Exception('Cannot load the script file: '.$file);
			}

			$tags .= '<script type="text/javascript" src="'.$file.'"></script>';
		}


		return $tags;
	}

	private function _getFiles()
	{
		$files = array();

		$base_path = '';

		foreach ($this->_xml->load->group as $group)
		{
			$base_path = $group->attributes()->base_path;

			foreach ($group->js as $js)
			{
				$files[] = $base_path.'/'.trim($js->attributes()->src, '/');
			}
		}

		return $files;
	}

	private function _loadXML($xml)
	{
		if (!file_exists($xml))
		{
			throw new Exception('Cannot locate the config file.');
		}

		if (!$this->_xml = simplexml_load_file($xml))
		{
			throw new Exception('Cannot load the xml file: '.$xml);
		}
	}

	private function _saveJS($str)
	{
		if (!file_put_contents($this->_output, $str))
		{
			throw new Exception('Cannot save into file: '.$this->_output);
		}
	}
}