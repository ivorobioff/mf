$(function(){
	Views.Category = Views.Abstract.View.extend({
		
		_template: $('#category-row'),
		
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
			
			this.model.on('change:group_id', $.proxy(function(model){
				this._reRender(Lib.Register.get('group_views').get('group_' + model.get('group_id')));
			}, this));
			
			this.model.on('destroy', $.proxy(function(){
				this.remove();
			}, this));
		},
		
		render: function(parent){
			var template = Handlebars.compile(this._template.html());
			
			this.setElement($(template(this.model.toJSON())));
			
			this.$el.insertBefore(parent.$el.find('#categories-hook'));
	
			return this;
		},
		
		_reRender: function(parent){
			this.remove();
			this.render(parent);
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});