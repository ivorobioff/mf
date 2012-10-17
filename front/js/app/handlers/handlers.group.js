/**
 * Класс посредник в оперяциях над группами категорий
 */
$(function(){
	Handlers.Group = Handlers.Abstract.Category.extend({
		handleCMenu: function(e){
			Views.GroupCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		}
	});
});