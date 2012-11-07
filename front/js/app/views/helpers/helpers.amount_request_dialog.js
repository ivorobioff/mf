Helpers.AmountRequestDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		var data = {
			requested_amount: this._view.getParam('requested_amount'),
			comment: this._view.getParam('comment')
		}
	
		Lib.Requesty.post({
			
			data: data,
			
			id: this._view.getModel('category').id,
			
			url: Resources.request_amount,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				error_handler.display();
				this._view.enableUI();
			}, this),
			
			followers: {
				'def': this._view.getModel('category'),
				'budget': Models.Budget.getInstance()
			}
			
		});
	}
	
});