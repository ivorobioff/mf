<?php
namespace Controller\Logs\Helpers;

class Logs
{
	static public function unsetEmpty(array $data)
	{
		foreach ($data as $key => $value)
		{
			if (trim($value) == '')
			{
				unset($data[$key]);
			}
		}

		return $data;
	}
}