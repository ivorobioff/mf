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
				this._delete_confirm = new Views.Confirmation('Вы уверены что хотите удалить данную категорию?', Helpers.DeleteCategoryDialog)
					.setModel('category', this._view.getContext().model);
			}
			
			this._delete_confirm.show();
		}		
	});
})