$(function(){
	Views.Abstract.Category = Views.Abstract.Refreshable.extend({
		
		_template: $('#category-row'),
		
		events: {
			'click .cat-item': function(e){
				Views.CategoryContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			}
		},
		
		render: function(parent){
			var template = Handlebars.compile(this._template.html());
			
			this.setElement($(template(this.model.toJSON())));
			
			this.$el.insertBefore(parent.$el.find('#categories-hook'));
	
			return this;
		}
	});
});