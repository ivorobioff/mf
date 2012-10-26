$(function(){
	Views.EditGroupDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#group-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.EditGroupDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Редактировать группу'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this.$el.find('[name=name]').val(_.escape(this.getModel('group').get('name')));
		}
	});
	
	Views.EditGroupDialog._INSTANCE = null;
	
	Views.EditGroupDialog.getInstance = function(){
		
		if (Views.EditGroupDialog._INSTANCE == null){
			Views.EditGroupDialog._INSTANCE = new Views.EditGroupDialog();
		}
		
		return Views.EditGroupDialog._INSTANCE;
	}
});