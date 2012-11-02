$(function(){
	Views.Budget = Views.Abstract.View.extend({
		_template: $('#tmp-main-header'),
		
		events: {
			'#menu a': function(){
				alert("sdf");
			}
		},
		
		initialize: function(){
			this.render();
		},
		
		render: function(){
			var template =  Handlebars.compile(this._template.html());
			this.setElement($(template(this.model.toJSON())));
			this.$el.insertAfter('#header-hook');
			this.delegateEvents();
		}
	});
});