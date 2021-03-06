Helpers.WithdrawalDialog = Helpers.Abstract.Helper.extend({
	
	_request_dialog: null,
	
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		var data = this._view.getDom().dataForSubmit();		
		var comment = data.comment;
		
		this._view.disableUI();
			
		Lib.Requesty.post({
			url: Resources.pseudo_category_withdrawal,
			
			data: data,
			
			id: this._view.getModel('category').id,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				if (_.isUndefined(error_handler.getData().requested_amount)){
					error_handler.display();
					this._view.enableUI();
					return ;
				}
				
				var requested_amount = error_handler.getData().requested_amount;
				
				if (_.isNull(this._request_dialog)){
					
					var text = 'Сумма не может быть снята, поскольку это превысит ваш план. '+
						'Увеличить запланированую сумму для данной категории?';
					
					this._request_dialog = new Views.Confirmation(text, Helpers.AmountRequestDialog);
				}
				
				this._view.enableUI();
				this._view.hide();
				
				this._request_dialog
					.addModel('category', this._view.getModel('category'))
					.assign('requested_amount', requested_amount)
					.assign('comment', comment)
					.show();
				
			}, this),
			
			followers: {
				def: this._view.getModel('category'),
				budget: Models.Budget.getInstance()
			}
		});
	}
});