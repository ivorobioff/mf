Helpers.EditGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		var data = this._view.getDom().dataForSubmit();
		
		this._view.disableUI();
		
		this._view.getModel('group').save(data, {
			
			wait: true,
			
			success: $.proxy(function(model){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(model, error_handler){
				this._view.enableUI();
				error_handler.display();
			}, this)
		});
	}
});