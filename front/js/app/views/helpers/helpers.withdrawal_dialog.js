Helpers.WithdrawalDialog = Helpers.Abstract.Helper.extend({
	
	_request_dialog: null,
	
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		var data = this._view.getDom().dataForSubmit();
		
		this._view.disableUI();
		
		this._view.getModel('category').save(data, {
			
			url: Resources.pseudo_category_withdrawal,
			
			wait: true,
			
			success: $.proxy(function(model, data){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(model, error_handler){
				if (_.isUndefined(error_handler.getData().zero)){
					error_handler.display();
					this._view.enableUI();
					this._view.hide();
					return ;
				}
				
				if (_.isNull(this._request_dialog)){
					var title = '';
					this._request_dialog = new Views.Confirmation('', Helpers.AmountRequestDialog);
				}
				
				this._view.enableUI();
				this._view.hide();
				
				this._request_dialog.addModel('category', this._view.getModel('category')).show();
				
			}, this)
		});
	}
});