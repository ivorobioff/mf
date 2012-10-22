$(function(){
	Views.Category = Views.Abstract.View.extend({
		
		el: $("#category-row"),
		_parent: null,
		
		events: {
			"click .cat-item": function(e){
				Views.CategoryContextMenu.getInstance().show({x: e.pageX, y: e.pageY});
				return false;
			}
		},
		
		render: function(){
			var template = Handlebars.compile(this.$el.html());
			this.$el = $(template(this.options.data));
			
			this.$el.insertBefore(this._parent.$el.find("#categories-hook"));
			
			this.delegateEvents();
			return this;
		},
		
		setParent: function(parent){
			this._parent = parent;
			return this;
		}
		
	});
});