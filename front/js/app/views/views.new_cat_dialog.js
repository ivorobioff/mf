$(function(){
	Views.NewCatDialog = Views.Abstract.Dialogs.extend({
		
		el: $("#new-cat-dlg"),
		
		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_handler = new Handlers.NewCatDialogHandler();
		}
	});
	
	Views.NewCatDialog._INSTANCE = null;
	
	Views.NewCatDialog.getInstance = function(){
		if (Views.NewCatDialog._INSTANCE == null){
			Views.NewCatDialog._INSTANCE = new Views.NewCatDialog();
		}
		
		return Views.NewCatDialog._INSTANCE;
	}
});