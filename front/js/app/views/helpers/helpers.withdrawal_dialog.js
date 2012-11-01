Helpers.WithdrawalDialog = Helpers.Abstract.Helper.extend({
	
	_request_dialog: null,
	
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		new Lib.Requesty().post({
			url: Resources.pseudo_category_withdrawal,
			
			data: _.extend(this._view.getDom().dataForSubmit(), {id: this._view.getModel('category').id}),
			
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
					.show();
				
			}, this),
			
			followers: this._view.getModel('category')
		});
	}
});