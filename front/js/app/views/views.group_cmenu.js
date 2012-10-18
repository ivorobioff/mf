$(function(){
	Views.GroupCMenu = Views.Abstract.ContextMenu.extend({
	
		el: $("#cm-groups"),
		
		_group_handler: null,
		
		events:{
			"click a": function(e){
				if (this._group_handler == null){
					this._group_handler = new Handlers.Group();
				}
				
				this._group_handler.doMenuAction($(e.target));
				
				return false;
			}
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