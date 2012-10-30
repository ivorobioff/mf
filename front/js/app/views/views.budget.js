$(function(){
	Views.Budget = Views.Abstract.View.extend({
		_template: $('#tmp-main-header'),
		
		initialize: function(){
			this.render();
		},
		
		render: function(){
			var template =  Handlebars.compile(this._template.html());
			this.setElement($(template()));
			this.$el.insertAfter('#header-hook');
		}
	});
});