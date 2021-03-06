/**
 * Класс для обработки контекстного меню групп
 */
$(function(){
	Helpers.GroupContextMenu = Helpers.Abstract.Menu.extend({
		
		_delete_confirmation: null,
		
		addCategory: function(){
			Views.NewCategoryDialog.getInstance().addModel('group', this._view.getContext().model).show();
		},
		
		editGroup: function(){
			Views.EditGroupDialog.getInstance().addModel('group', this._view.getContext().model).show();
		},
		
		deleteGroup: function(){
			
			if (_.isNull(this._delete_confirmation)){
				var text = 'Вы уверены, что хотите удалить данную группу?';
				this._delete_confirmation = new Views.Confirmation(text, Helpers.DeleteGroupConfirmation);
			}
			
			this._delete_confirmation.addModel('group', this._view.getContext().model).show();
		}
	});
});