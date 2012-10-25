/**
 * Класс для обработки контекстного меню групп
 */
$(function(){
	Helpers.GroupContextMenu = Helpers.Abstract.ContextMenu.extend({
		addCategory: function(){
			Views.NewCategoryDialog.getInstance().show();
		}
	});
});