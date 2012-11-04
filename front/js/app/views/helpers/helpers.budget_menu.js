Helpers.BudgetMenu = Helpers.Abstract.Menu.extend({
	
	_withdrawal_prompt: null,
	_deposit_prompt: null,
	
	withdrawal: function(){
		if (this._withdrawal_prompt == null){
			this._withdrawal_prompt = new Views.Prompt({
				title: 'Снять сумму',
				label: 'Сумма'
			}, Helpers.BudgetWithdrawalPrompt);
		}
		
		this._withdrawal_prompt.addModel('budget', this._view.model).show();
	},
	
	deposit: function(){
		if (this._deposit_prompt == null){
			this._deposit_prompt = new Views.Prompt({
				title: 'Внести сумму',
				label: 'Сумма'
			}, Helpers.BudgetDepositPrompt);
		}
		
		this._deposit_prompt.addModel('budget', this._view.model).show();
	}
});