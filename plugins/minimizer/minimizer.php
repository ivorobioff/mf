<?php
namespace Plugins\Minimizer;

use \Plugins\Minimizer\JSMin;
use \Plugins\Minimizer\JSMinException;
use \Plugins\Minimizer\Exception;
/**
 * Класс для объединения и минимизации всех js добавленых в стэк.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Minimizer
{
	private $_source = array();

	private $_destination = '';

	private $_js_min = '';

	private $_base_path = '';

	public function process()
	{
		foreach ($this->_source as $path)
		{
			if (!file_exists($path))
			{
				throw new Exception('Cannot load the file: '.$path);
			}

			$this->_js_min .= JSMin::minify(file_get_contents($path));
		}

		if ($this->_js_min)
		{
			$this->_saveJS(trim($this->_js_min));
		}

		$this->clear();
	}

	public function basePath($path)
	{
		$this->_base_path = trim($path, '/');
		return $this;
	}

	private function _saveJS($str)
	{
		if (!file_put_contents($this->_destination, $str))
		{
			throw new Exception('Cannot save into file: '.$this->_destination);
		}
	}

	public function source($path)
	{
		$this->_source[] = $this->_base_path.'/'.trim($path, '/');

		return $this;
	}

	public function destination($file)
	{
		$this->_destination = $file;

		return $this;
	}

	public function clear()
	{
		$this->_source = array();
		$this->_base_path = '';
		$this->_destination = '';
		$this->_js_min = '';
	}
}