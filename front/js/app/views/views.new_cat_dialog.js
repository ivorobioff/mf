$(function(){
	Views.newCatDialog = Views.Abstract.Dialogs.extend({
		el: $("#new-cat-dlg")
	});
	
	Views.newCatDialog._INSTANCE = null;
	
	Views.newCatDialog.getInstance = function(){
		if (Views.newCatDialog._INSTANCE == null){
			Views.newCatDialog._INSTANCE = new Views.newCatDialog();
		}
		
		return Views.newCatDialog._INSTANCE;
	}
});