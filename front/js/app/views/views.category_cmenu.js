$(function(){
	Views.CategoryCMenu = Views.Abstract.ContextMenu.extend({
		
		el: $("#cm-cats")
		
	});
	
	Views.CategoryCMenu.getInstance = function(){
		
		if (Views.CategoryCMenu._INSTANCE == null){
			Views.CategoryCMenu._INSTANCE = new Views.CategoryCMenu();
		}
		
		return Views.CategoryCMenu._INSTANCE;
	}
});