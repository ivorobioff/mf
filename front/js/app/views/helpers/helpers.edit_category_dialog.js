/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.EditCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			var data = this._view.getDom().dataForSubmit();
			
			this._view.disableUI();
			
			this._view.getModel('category').save(data, {
				wait: true,
				success:  $.proxy(function(model, data){
					this._view.enableUI();
					this._view.hide();
				}, this),
				error: function(model, error_handler){
					this._view.enableUI();
					error_handler.display();
				}
			});
		}
	});
});