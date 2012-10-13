<?php
namespace System\Ajax;

/**
 * Обработчик ajax ответов.
 * Испльзуется для отправки json ответом клиенту при ajax запросах.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Responder
{
	private $_template;

	private $_data = array();

	public function attachTemplate(Templates $template)
	{
		$this->_template = $template;
		return $this;
	}

	public function attachData(array $data)
	{
		$this->_data = $data;
		return $this;
	}

	public function sendResponse()
	{
		$data = array('status' => 'ok');
		$this->_send($data);
	}

	public function sendError()
	{
		$data = array('status' => 'error');
		$this->_send($data);
	}

	private function _send(array $data)
	{
		if ($this->_data)
		{
			$data['data'] = $this->_data;
		}

		if ($this->_template instanceof Templates)
		{
			$data['template'] = $this->_template->render();
		}

		echo json_encode($data);
	}
}