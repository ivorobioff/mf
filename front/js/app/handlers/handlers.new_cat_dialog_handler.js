/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Handlers.NewCatDialogHandler = Handlers.Interface.DialogActions.extend({
		doCancel: function(dialog){
			dialog.hide();
		},
		
		doSubmit: function(dialog){
			
			var data = dialog.getDom().dataForSubmit();
			
			new Models.Category().save(data, {
				success:  function(model, data){
					Collections.Categories.getInstance().add(model);
					dialog.hide();
				}, 
				error: function(model, error_handler){
					error_handler.displayFormErrors();
				}
			});
		}
	});
});