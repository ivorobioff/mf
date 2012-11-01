/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.NewCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			
			this._view.disableUI();
			
			new Lib.Requesty().create({
				
				data: this._view.getDom().dataForSubmit(),
				
				url: Resources.category,
				
				success: $.proxy(function(model){
					Collections.Categories.getInstance().add(model);
					this._view.enableUI();
					this._view.hide();
				}, this),
				
				error: $.proxy(function(errors){
					this._view.enableUI();
					errors.display();
				}, this),
				
				followers: new Models.Category()
			});
		}
	});
});