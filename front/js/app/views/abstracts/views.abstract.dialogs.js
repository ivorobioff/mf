$(function(){
	Views.Abstract.Dialogs = Backbone.View.extend({
		
		_is_shown: false,
		
		initialize: function(){
			this.render();
		},
		
		render: function(){
			this.$el = $(this.$el.html());
			$("body").append(this.$el);
		},
		
		show: function(){
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
		}
		
	});
});