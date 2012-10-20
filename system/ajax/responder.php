<?php
namespace System\Ajax;

/**
 * Обработчик ajax ответов.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Responder
{
	public function sendResponse(array $data = array())
	{
		$this->_send($data);
	}

	public function sendError(array $data = array())
	{
		header(':', true, 403);

		$this->_send($data);
	}

	private function _send(array $data)
	{
		echo json_encode($data);
	}
}