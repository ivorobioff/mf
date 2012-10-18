/**
 * Класс посредник в оперяциях над группами категорий
 */
$(function(){
	Handlers.Group = Handlers.Abstract.Category.extend({
		
		showCMenu: function(e){
			Views.GroupCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		},
		
		doMenuAction: function($el){
			switch ($el.attr("action")){
				case 'add-category':
					Views.newCatDialog.getInstance().show();
					Views.GroupCMenu.getInstance().hide();
					break;
			}
		}
	});
});