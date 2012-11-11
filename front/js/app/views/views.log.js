$(function(){
	Views.Log = Views.Abstract.View.extend({
		
		_template: $('#log-row'),
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.model.on('destroy', function(){
				this.remove();
			}, this);
			
			this.render();
		},
		
		render: function(){
			var tmp = Handlebars.compile(this._template.html());
			this.setElement($(tmp(this.model.toJSON())));
			
			this.$el.insertAfter($('#log-hook'));
		}
	});
});