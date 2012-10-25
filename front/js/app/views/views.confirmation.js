$(function(){
	Views.Confirmation = Views.Abstract.Dialogs.extend({
		
		_text: '',
		
		_template: $('#confirmation-dialog'),
	
		initialize: function (text, helper){
			this._text = text;
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);			
			this._dialog_helper = new helper(this);
		},
		
		_getLayoutData: function(){
			return {
				title:'Внимание!',
				submit: 'Да',
				cancel: 'Нет'
			};
		},
		
		_getContentData: function(){
			return {text: this._text};
		}
	});
});