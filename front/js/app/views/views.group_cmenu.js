$(function(){
	Views.GroupCMenu = Views.Abstract.ContextMenu.extend({
	
		el: $("#cm-groups")
	
	});
	
	Views.GroupCMenu.getInstance = function(){
		
		if (Views.GroupCMenu._INSTANCE == null){
			Views.GroupCMenu._INSTANCE = new Views.GroupCMenu();
		}
		
		return Views.GroupCMenu._INSTANCE;
	}
});