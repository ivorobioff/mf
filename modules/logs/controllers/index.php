<?php
namespace Controller\Logs;

use System\Lib\Http;
use Plugins\Validator\Validator;
use Plugins\Validator\Rules\TextLength;
use Plugins\Validator\Rules\DateFormat;
use \Controller\Common\Layout;
use \Model\Logs\Logs as ModelLogs;

class Index extends Layout
{
	const VALID_WRONG_DATE = 'Не верный формат';
	const VALID_TEXT_TOO_SHORT = 'Минимум 4 символа';

	const MIN_KEYWORD_LENGTH = 4;

	public function index()
	{
		$this->_render('logs/index.phtml');
	}

	public function readLogs()
	{
		$data = Helpers\Logs::unsetEmpty(Http::post());

		$validator_rules = array();

		if (always_set($data, 'from'))
		{
			$validator_rules['from'] = new TimeFormat('Y-m-d', self::VALID_WRONG_DATE);
		}

		if (always_set($data, 'to'))
		{
			$validator_rules['to'] = new DateFormat('Y-m-d', self::VALID_WRONG_DATE);
		}

		if (always_set($data, 'keyword'))
		{
			$validator_rules['keyword'] = new TextLength(self::MIN_KEYWORD_LENGTH, null, self::VALID_TEXT_TOO_SHORT);
		}

		if (!Validator::isValid($data, $validator_rules))
		{
			return $this->_sendError(Validator::fetchErrors());
		}

		$logs = new ModelLogs();

		return $this->_sendResponse($logs->getAll($data)->toArray());
	}
}