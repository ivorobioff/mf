$(function(){
	Views.NewGroupDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#group-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.NewGroupDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Создать новую группу'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this.$el.find('[name=name]').val('');
		}
	});
	
	Views.NewGroupDialog._INSTANCE = null;
	
	Views.NewGroupDialog.getInstance = function(){
		
		if (Views.NewGroupDialog._INSTANCE == null){
			Views.NewGroupDialog._INSTANCE = new Views.NewGroupDialog();
		}
		
		return Views.NewGroupDialog._INSTANCE;
	}
});