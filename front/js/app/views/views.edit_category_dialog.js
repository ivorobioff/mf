$(function(){
	Views.EditCategoryDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#category-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.EditCategoryDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Редактировать категорию'};
		},
		
		_update: function(){
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			
			var options = '';
			var groups = Collections.Groups.getInstance().toJSON();
			
			for (var i in groups){
				options +='<option value=\'' + groups[i].id + '\'>' + groups[i].name + '</option>';
			}
	
			this.$el.find('#groups-select').html(options);
			
			this.$el.find('[name=title]').val(this.getModel('category').get('title'));
			this.$el.find('[name=amount]').val(this.getModel('category').get('amount'));
			
			this.$el.find('[name=pin]').removeAttr('checked');
			
			if (this.getModel('category').get('pin') > 0){
				this.$el.find('[name=pin]').attr('checked', 'checked');
			} 
			
			this.$el.find('[name=group_id]').val(this.getModel('category').get('group_id'));
		}
	});
	
	Views.EditCategoryDialog._INSTANCE = null;
	
	Views.EditCategoryDialog.getInstance = function(){
		
		if (Views.EditCategoryDialog._INSTANCE == null){
			Views.EditCategoryDialog._INSTANCE = new Views.EditCategoryDialog();
		}
		
		return Views.EditCategoryDialog._INSTANCE;
	}
});