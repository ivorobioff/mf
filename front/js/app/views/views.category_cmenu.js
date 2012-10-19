$(function(){
	Views.CategoryCMenu = Views.Abstract.ContextMenu.extend({
		
		el: $("#cm-cats"),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_handler = new Handlers.CategoryContextMenu();
		}
	});	
	
	Views.CategoryCMenu._INSTANCE = null;
	
	Views.CategoryCMenu.getInstance = function(){
		
		if (Views.CategoryCMenu._INSTANCE == null){
			Views.CategoryCMenu._INSTANCE = new Views.CategoryCMenu();
		}
		
		return Views.CategoryCMenu._INSTANCE;
	}
});