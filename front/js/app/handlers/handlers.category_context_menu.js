/**
 * Класс для обработки контекстого меню категорий
 */
$(function(){
	Handlers.CategoryContextMenu = Handlers.Interface.ContextMenu.extend({
		
		show: function(e){
			Views.CategoryCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		},
		
		doAction: function(e){
			alert("wow");
		},
	});
})