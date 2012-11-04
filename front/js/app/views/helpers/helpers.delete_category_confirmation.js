Helpers.DeleteCategoryConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		
		Lib.Requesty.remove({
			
			url: Resources.category,
			
			data: {id: this._view.getModel('category').id},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				this._view.hide();
				error_handler.display();
			}, this),
			
			followers: {
				delete_models: this._view.getModel('category'),
				update_models: Models.Budget.getInstance()
			}
		});
	}
});