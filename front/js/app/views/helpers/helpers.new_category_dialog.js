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
			
			Lib.Requesty.create({
				
				data: this._view.getDom().dataForSubmit(),
				
				url: Resources.category,
				
				success: $.proxy(function(followers){
					Collections.Categories.getInstance().add(followers.def);
					this._view.enableUI();
					this._view.hide();
				}, this),
				
				error: $.proxy(function(errors){
					this._view.enableUI();
					errors.display();
				}, this),
				
				followers: {
					def: new Models.Category(),
					budget: Models.Budget.getInstance()
				}
			});
		}
	});
});