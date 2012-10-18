/**
 * Класс посредник в оперяциях над категориями
 */
$(function(){
	Handlers.Category = Handlers.Abstract.Category.extend({
		
		showCMenu: function(e){
			Views.CategoryCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		},
		
		doMenuAction: function($el){
			alert($el.attr('action'));
		}
	});
})