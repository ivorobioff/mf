Helpers.NewGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		new Lib.Requesty().create({
			
			data: this._view.getDom().dataForSubmit(),
			
			url: Resources.group,
			
			success: $.proxy(function(model){
				Collections.Groups.getInstance().add(model);
				new Views.Group({model: model});
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				this._view.enableUI();
				errors.display();
			}, this),
			
			followers: new Models.Group()
		});
	}
});