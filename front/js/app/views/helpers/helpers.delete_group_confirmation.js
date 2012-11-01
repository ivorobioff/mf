Helpers.DeleteGroupConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		
		
		Lib.Requesty.remove({
			
			url: Resources.group,
			
			data: {id: this._view.getModel('group').id},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				this._view.hide();
				error_handler.display();
			}, this),
			
			followers: this._view.getModel('group')
		});
	}
});