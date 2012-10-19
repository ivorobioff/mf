/**
 * Класс для обработки контекстного меню групп
 */
$(function(){
	Handlers.GroupContextMenu = Handlers.Interface.ContextMenu.extend({
		
		show: function(e){
			Views.GroupCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		},
		
		doAction: function(e){
			switch ($(e.target).attr("action")){
				case 'add-category':
					Views.NewCatDialog.getInstance().show();
					Views.GroupCMenu.getInstance().hide();
					break;
			}
		},
		
		onSubmitAction: function(){
			
		}
	});
});