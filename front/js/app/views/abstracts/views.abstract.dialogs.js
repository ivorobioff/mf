$(function(){
	Views.Abstract.Dialogs = Views.Abstract.View.extend({
		
		_is_shown: false,
		_dialog_helper: null,
		
		events: {
			"click .cancel-button, .dlg-close": function(){
				
				this._dialog_helper.doCancel();
				
				return false;
			},
			"click .submit-button": function(){
				
				this._dialog_helper.doSubmit();
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
	});
});