<?php
namespace Lib\Operations\AmountChange;

use \Lib\Operations\AmountChange\Abstractions\AmountChange;
use \Model\Operations\Categories as ModelCategories;
use \Lib\Common\FrontErrors;

class FlowListener extends AmountChange
{
	/**
	 * Флоу слушатель. Запускается каждый раз когда  категорие задается сумма.
	 * @param $amount_diff - разница в сумме до и после измеенения.
	 * Может быть как больше так и меньше либо равно нулю.
	 * @param $id - id категории
	 * (non-PHPdoc)
	 * @see Lib\Operations\AmountChange\Abstractions.AmountChange::onAmountChange()
	 */
	public function onAmountChange($amount_diff, $id)
	{
		$cat = new ModelCategories($id);

		$cat->deposit($amount_diff);
	}

	/**
	 * Запускается для теста окружения.
	 * (non-PHPdoc)
	 * @see Lib\Operations\AmountChange\Abstractions.AmountChange::test()
	 */
	public function test($amount_diff, $id)
	{
		$cat = new ModelCategories($id);

		if (($cat->getCurrentAmount() + $amount_diff) < 0)
		{
			throw new FrontErrors('Текущая сумма не может быть меньше нуля');
		}
	}
}