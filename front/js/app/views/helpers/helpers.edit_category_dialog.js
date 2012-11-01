/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.EditCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			
			this._view.disableUI();
			
			Lib.Requesty.update({
				
				url: Resources.category,
				
				data: this._view.getDom().dataForSubmit(),
				
				id: this._view.getModel('category').id,
				
				success: $.proxy(function(){
					this._view.enableUI();
					this._view.hide();
				}, this),
				
				error: $.proxy(function(error_handler){
					this._view.enableUI();
					error_handler.display();
				}, this),
				
				followers: this._view.getModel('category')
			});
		}
	});
});