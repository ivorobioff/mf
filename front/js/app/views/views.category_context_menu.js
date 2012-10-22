$(function(){
	Views.CategoryContextMenu = Views.Abstract.ContextMenu.extend({
		el: $("#cm-cats"),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_helper = new Helpers.CategoryContextMenu(this);
		}
	});	
	
	Views.CategoryContextMenu._INSTANCE = null;
	
	Views.CategoryContextMenu.getInstance = function(){
		
		if (Views.CategoryContextMenu._INSTANCE == null){
			Views.CategoryContextMenu._INSTANCE = new Views.CategoryContextMenu();
		}
		
		return Views.CategoryContextMenu._INSTANCE;
	}
});