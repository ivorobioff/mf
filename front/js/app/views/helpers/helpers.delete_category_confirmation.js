Helpers.DeleteCategoryConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		
		this._view.getModel('category').destroy({
			
			wait: true,
			
			option: $.proxy(function(model, data){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(model, error_handler){
				this._view.enableUI();
				this._view.hide();
				error_handler.display();
			}, this)
		});
		this._view.hide();
	}
});