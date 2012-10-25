$(function(){
	Views.Confirmation = Views.Abstract.Dialogs.extend({
		
		_text: '',
		
		_template: $('#confirmation-dialog'),
		
		/**
		 * Должен содержать две функции которые будут привязаны к кнопкам "Да" и "Нет"
		 * @private
		 */
		_actions: null,
		
		initialize: function (text, actions){
			this._text = text;
			this._actions = actions;
			
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);			
			this._dialog_helper = new Helpers.ConfirmationDialog(this);
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
		},
		
		getActions: function(){
			return this._actions;
		}
	});
});