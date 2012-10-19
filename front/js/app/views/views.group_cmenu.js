$(function(){
	Views.GroupCMenu = Views.Abstract.ContextMenu.extend({
	
		el: $("#cm-groups"),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_handler = new Handlers.GroupContextMenu();
		}
	
	});
	
	Views.GroupCMenu._INSTANCE = null;
	
	Views.GroupCMenu.getInstance = function(){
		
		if (Views.GroupCMenu._INSTANCE == null){
			Views.GroupCMenu._INSTANCE = new Views.GroupCMenu();
		}
		
		return Views.GroupCMenu._INSTANCE;
	}
});