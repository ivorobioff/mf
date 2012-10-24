$(function(){
	Views.GroupContextMenu = Views.Abstract.ContextMenu.extend({
	
		_template: $('#cm-groups'),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_helper = new Helpers.GroupContextMenu(this);
		}
	
	});
	
	Views.GroupContextMenu._INSTANCE = null;
	
	Views.GroupContextMenu.getInstance = function(){
		
		if (Views.GroupContextMenu._INSTANCE == null){
			Views.GroupContextMenu._INSTANCE = new Views.GroupContextMenu();
		}
		
		return Views.GroupContextMenu._INSTANCE;
	}
});