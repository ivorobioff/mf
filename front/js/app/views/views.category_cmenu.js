$(function(){
	Views.CategoryCMenu = Views.Abstract.ContextMenu.extend({
		initialize: function(){
			this.render();
		}
	});
	
	Views.CategoryCMenu.getInstance = function(settings){
		
		if (Views.CategoryCMenu._INSTANCE == null){
			Views.CategoryCMenu._INSTANCE = new Views.CategoryCMenu(settings);
		}
		
		return Views.CategoryCMenu._INSTANCE;
	}
});