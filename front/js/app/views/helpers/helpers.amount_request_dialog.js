Helpers.AmountRequestDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		var data = {
			requested_amount: this._view.getParam('requested_amount'),
			id: this._view.getModel('category').id
		}
	
		Lib.Requesty.post({
			
			data: data,
			
			url: Resources.request_amount,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				error_handler.display();
				this._view.enableUI();
			}, this),
			
			followers:  this._view.getModel('category'),
			
		});
	}
	
});