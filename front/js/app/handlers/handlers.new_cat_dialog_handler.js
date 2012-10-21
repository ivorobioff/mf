/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Handlers.NewCatDialogHandler = Handlers.Interface.DialogActions.extend({
		doCancel: function(dialog){
			dialog.hide();
		},
		
		doSubmit: function(dialog){
			var cat_model = new Models.Category();
			
			var data = dialog.getDom().dataForSubmit();
			
			cat_model.save(data, {
				success:  function(model, data){
					dialog.hide();
				}, 
				error: function(model, error_handler){
					error_handler.displayFormErrors();
				}
			});
		}
	});
});