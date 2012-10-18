$(function(){
	Views.Abstract.Dialogs = Class.extend({
			
		_real_dialog: null,
		
		render: function(){
			this._real_dialog = $(this.$el.html());
			$("body").prepend();
		},
		
		show: function(){
			
		},
		
		hide: function(){
			
		}
	});
});