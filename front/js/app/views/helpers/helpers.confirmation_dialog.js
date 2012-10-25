Helpers.ConfirmationDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		if (_.isFunction(this._view.getActions().no)){
			this._view.getActions().no();
		}
	},
	
	doSubmit: function(){
		if (_.isFunction(this._view.getActions().yes)){
			this._view.getActions().yes();
		}
	}
});