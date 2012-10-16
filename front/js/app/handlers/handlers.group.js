/**
 * Класс посредник в оперяциях над группами категорий
 */
$(function(){
	Handlers.Group = Handlers.Abstract.Category.extend({
	    context_menu: new Views.GroupCMenu({el: $("#cm-groups")})
	});
});