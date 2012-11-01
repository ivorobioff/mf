Helpers.ReturnAmountConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		Lib.Requesty.post({
			url: Resources.return_amount,
			id: this._view.getModel('category').id,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				this._view.enableUI();
				this._view.hide();
				errors.display();
			}, this),
			
			followers: this._view.getModel('category')
		});
	}
});