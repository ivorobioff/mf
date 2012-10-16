/**
 * Класс посредник в оперяциях над категориями
 */
$(function(){
	Handlers.Category = Handlers.Abstract.Category.extend({
		
		context_menu: null,
		
		initialize: function(){
			this.context_menu = Views.CategoryCMenu.getInstance({el: $("#cm-cats")});
		}
	});
})