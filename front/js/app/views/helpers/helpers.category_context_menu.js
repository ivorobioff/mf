/**
 * Класс для обработки контекстого меню категорий
 */
$(function(){
	Helpers.CategoryContextMenu = Helpers.Abstract.ContextMenu.extend({	
		
		_delete_confirm: null,
		
		editCategory: function(){
			Views.EditCategoryDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		deleteCategory: function(){
						
			if (_.isNull(this._delete_confirm)){
				
				var func = {};
				
				this._delete_confirm = new Views.Confirmation('Вы уверены что хотите удалить данную категорию?', func);
			
				$.extend(func, {
					yes: $.proxy(function(){
						this._delete_confirm.hide();
					}, this),
					
					no: $.proxy(function(){
						this._delete_confirm.hide();
					}, this)
				});
			}
			
			this._delete_confirm.show();
		}		
	});
})