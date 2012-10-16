$(function(){
	Views.GroupCMenu = Views.Abstract.ContextMenu.extend({
		initialize: function(){
			this.render();
		}
	});
	
	Views.GroupCMenu.getInstance = function(settings){
		
		if (Views.GroupCMenu._INSTANCE == null){
			Views.GroupCMenu._INSTANCE = new Views.GroupCMenu(settings);
		}
		
		return Views.GroupCMenu._INSTANCE;
	}
});