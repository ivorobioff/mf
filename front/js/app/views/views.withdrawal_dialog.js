$(function(){
	Views.WithdrawalDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#withdrawal-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.WithdrawalDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Cнять сумму'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this.$el.find('[name=amount]').val('');
		}
	});
	
	Views.WithdrawalDialog._INSTANCE = null;
	
	Views.WithdrawalDialog.getInstance = function(){
		
		if (Views.WithdrawalDialog._INSTANCE == null){
			Views.WithdrawalDialog._INSTANCE = new Views.WithdrawalDialog();
		}
		
		return Views.WithdrawalDialog._INSTANCE;
	}
});