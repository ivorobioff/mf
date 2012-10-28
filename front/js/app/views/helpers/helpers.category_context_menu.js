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
				var title = 'Вы уверены что хотите удалить данную категорию?';
				this._delete_confirm = new Views.Confirmation(title, Helpers.DeleteCategoryConfirmation);
			}
			
			this._delete_confirm.addModel('category', this._view.getContext().model).show();
		},
		
		withdrawal: function(){
			Views.WithdrawalDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		returnAmount: function(){
			
		}
	});
})