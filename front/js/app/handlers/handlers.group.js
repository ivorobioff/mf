/**
 * Класс посредник в оперяциях над группами категорий
 */
$(function(){
	Handlers.Group = Handlers.Abstract.Category.extend({
		
	    context_menu: null,
	    
	    initialize: function(){
	    	this.context_menu = Views.GroupCMenu.getInstance({el: $("#cm-groups")});
	    }
	    
	});
});