<?php
namespace System\Test;
/**
 * Контрролер тестов. Запускает тест кейсы.
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Controller
{
	private $_class_name;

	public function __construct($class_name)
	{
		$this->_class_name = $class_name;
	}

	public function run()
	{
		$suite = new \PHPUnit_Framework_TestSuite();
		$suite->setName($this->_class_name);

		$suite->addTestSuite($this->_class_name);

		$listener = new \PHPUnit_Util_Log_TAP();

		$testResult = new \PHPUnit_Framework_TestResult();
		$testResult->convertErrorsToExceptions(true);

		$testResult->addListener($listener);

		echo '<pre>';
		$suite->run($testResult);
		echo '</pre>';
	}
}