/**
 * Класс посредник в оперяциях над категориями
 */
$(function(){
	Handlers.Category = Handlers.Abstract.Category.extend({
		context_menu: new Views.CategoryCMenu({el: $("#cm-cats")})
	});
})