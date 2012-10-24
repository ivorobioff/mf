$(function(){
	Views.Category = Views.Abstract.View.extend({
		
		_template: $('#category-row'),
		
		_parent: null,
		
		events: {
			'click .cat-item': function(e){
				Views.CategoryContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			}
		},
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
		},
		
		render: function(){
			var template = Handlebars.compile(this._template.html());
			
			this.setElement($(template(this.model.toJSON())));
			
			this.$el.insertBefore(this._parent.$el.find('#categories-hook'));
	
			return this;
		},
		
		refresh: function(){
			var template = Handlebars.compile(this._template.html());
			
			var temp = $(template(this.model.toJSON()));
			
			temp.insertAfter(this.$el);
			
			this.$el.remove();
			
			this.setElement(temp);
	
			return this;
		},
		
		setParent: function(parent){
			this._parent = parent;
			return this;
		}
		
	});
});