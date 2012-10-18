$(function(){
	Views.CategoryCMenu = Views.Abstract.ContextMenu.extend({
		
		el: $("#cm-cats"),
		
		_category_handler: null,
		
		events:{
			"click a": function(e){
				if (this._category_handler == null){
					this._category_handler = new Handlers.Category();
				}
				
				this._category_handler.doMenuAction($(e.target));
				
				return false;
			}
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