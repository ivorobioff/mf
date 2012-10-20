$(function(){
	Views.Abstract.Dialogs = Backbone.View.extend({
		
		_is_shown: false,
		_dialog_handler: null,
		_data: null,
		
		events: {
			"click .cancel-button, .dlg-close": function(){
				if (this._dialog_handler instanceof  Handlers.Interface.DialogActions){
					this._dialog_handler.doCancel();
				}
				
				return false;
			},
			"click .submit-button": function(){
				if (this._dialog_handler instanceof  Handlers.Interface.DialogActions){
					this._dialog_handler.doSubmit();
				}
			}
		},
		
		initialize: function(){
			this.render();
		},
		
		render: function(){
			this.$el = $(this.$el.html());
			$("body").append(this.$el);
		},
		
		_update: function(){
			
		},
		
		show: function(){
			this._update();
			this.$el.show();
			this._adjustWindow();
			this._is_shown = true;
		},
		
		hide: function(){
			this.$el.hide();
			this._is_shown = false;
		},
		
		isShown: function(){
			return this._is_shown;
		},
		
		_adjustWindow: function(){
			
			var $dlg = this.$el.find(".dlg");
			
			var top = Math.round($dlg.height() / 2);
			
			$dlg.css("margin-top", "-"+top+"px");
		},
		
		setData: function(data){
			this._data = data;
			return this;
		}
		
	});
});