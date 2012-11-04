Helpers.BudgetDepositPrompt = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		Lib.Requesty.post({
			url: Resources.budget_deposit,
			data: {amount: this._view.getValue()},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				errors.display();
				this._view.enableUI();
			}, this),
			
			followers: this._view.getModel('budget')
		});
	}
});