$(function(){
	Views.NewCatDialog = Views.Abstract.Dialogs.extend({
		
		el: $("#new-cat-dlg"),
		
		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_handler = new Handlers.NewCatDialogHandler();
		}, 
		
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			
			if (!_.isObject(this._data)){
				return false;
			}
			
			var options = '';
			
			for (var i in this._data){
				options +="<option value=\"" + this._data[i].id + "\">" + this._data[i].name + "</option>";
			}
			
			this.$el.find("#groups-select").html(options);
		}
	});
	
	Views.NewCatDialog._INSTANCE = null;
	
	Views.NewCatDialog.getInstance = function(){
		if (Views.NewCatDialog._INSTANCE == null){
			Views.NewCatDialog._INSTANCE = new Views.NewCatDialog();
		}
		
		return Views.NewCatDialog._INSTANCE;
	}
});