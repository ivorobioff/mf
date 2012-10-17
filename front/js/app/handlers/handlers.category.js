/**
 * Класс посредник в оперяциях над категориями
 */
$(function(){
	Handlers.Category = Handlers.Abstract.Category.extend({
		handleCMenu: function(e){
			Views.CategoryCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		}
	});
})