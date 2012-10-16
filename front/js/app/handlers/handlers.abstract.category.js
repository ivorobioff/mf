/**
 * Абстрактный класс посредник для категорий и групп 
 */
$(function(){
	Handlers.Abstract.Category = Class.extend({
		context_menu: null,
			
	    handleCMenu: function(e){	        
	        this.context_menu.show({x: e.pageX, y: e.pageY});
	    }
	});
})