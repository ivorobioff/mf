/**
 * Класс для обработки контекстого меню категорий
 */
$(function(){
	Helpers.CategoryContextMenu = Helpers.Abstract.ContextMenu.extend({	
		
		_delete_confirm: null,
		_return_confirm: null,
		
		editCategory: function(){
			Views.EditCategoryDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		deleteCategory: function(){
						
			if (_.isNull(this._delete_confirm)){				
				var text = 'Вы уверены что хотите удалить данную категорию?';
				this._delete_confirm = new Views.Confirmation(text, Helpers.DeleteCategoryConfirmation);
			}
			
			this._delete_confirm.addModel('category', this._view.getContext().model).show();
		},
		
		withdrawal: function(){
			Views.WithdrawalDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		returnAmount: function(){
			
			if (_.isNull(this._return_confirm)){
				var text = 'Вы уверены, что хотите вернуть оставшуюся сумму?';
				this._return_confirm = new Views.Confirmation(text, Helpers.ReturnAmountConfirmation);
			}
			
			this._return_confirm.addModel('category', this._view.getContext().model).show();
		}
	});
})