/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Handlers.NewCatDialogHandler = Handlers.Interface.DialogActions.extend({
		doCancel: function(){
			Views.NewCatDialog.getInstance().hide();
		},
		
		doSubmit: function(){
			Views.NewCatDialog.getInstance().hide();
		}
	});
});