Helpers.NewGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		var data = this._view.getDom().dataForSubmit();
		
		this._view.disableUI();
		
		new Models.Group().save(data, {
			success: $.proxy(function(model){
				
				Collections.Groups.getInstance().add(model);
				
				new Views.Group({model: model});	
				
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