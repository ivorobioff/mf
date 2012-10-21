$(function(){
	Views.Category = Views.Abstract.View.extend({
		el: $("#category-row"),
		_parent: null,
		
		events: {
			"click .cat-item": function(e){
				if (this._category_handler == null){
					this._category_handler = new Handlers.CategoryContextMenu();
				}
				
				this._category_handler.show(e);
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