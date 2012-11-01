Helpers.EditGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){		
		this._view.disableUI();
		
		new Lib.Requesty().update({
			
			url: Resources.group,
			
			data: _.extend(this._view.getDom().dataForSubmit(), {id: this._view.getModel('group').id}),
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				error_handler.display();
			}, this),
			
			followers: this._view.getModel('group')
		});
	}
});