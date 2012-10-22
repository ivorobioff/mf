/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.NewCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			var data = this._view.getDom().dataForSubmit();
			
			new Models.Category().save(data, {
				success:  $.proxy(function(model, data){
					Collections.Categories.getInstance().add(model);
					this._view.hide();
				}, this), 
				
				error: function(model, error_handler){
					error_handler.display();
				}
			});
		}
	});
});