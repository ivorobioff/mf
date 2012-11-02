$(function(){
	Views.Prompt = Views.Abstract.Dialogs.extend({
		
		_options: null,
		
		_template: $('#prompt-dialog'),
			
		initialize: function (options, helper){
			this._options = options;
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);			
			this._dialog_helper = new helper(this);
		},
		
		_getLayoutData: function(){
			return {
				title:this._options.title,
				submit: 'Применить',
				cancel: 'Отмена'
			};
		},
		
		_getContentData: function(){
			return {label: this._options.label};
		},
		
		getValue: function(){
			return this.$el.find('[name=value]').val();
		}
	});
});