$(function(){
	Views.NewCategoryDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#category-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.NewCategoryDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Создать новую категорию'};
		},
		
		/**
		 * Запускается перед тем как окно появляется. 
		 * Служит для обновления данных окна перед появлением самого окна.
		 * @protected
		 */
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			
			var options = '';
			var groups = Collections.Groups.getInstance().toJSON();
			
			for (var i in groups){
				options +='<option value=\'' + groups[i].id + '\'>' + groups[i].name + '</option>';
			}
			
			this.$el.find('#groups-select').html(options);
			
			this.$el.find('[name=title], [name=amount]').val('');
			this.$el.find('[name=pin]').removeAttr('checked');
						
			this.$el.find('[name=group_id]').val(this.getModel('group').id);
		}
	});
	
	Views.NewCategoryDialog._INSTANCE = null;
	
	Views.NewCategoryDialog.getInstance = function(){
		
		if (Views.NewCategoryDialog._INSTANCE == null){
			Views.NewCategoryDialog._INSTANCE = new Views.NewCategoryDialog();
		}
		
		return Views.NewCategoryDialog._INSTANCE;
	}
});