/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.View.extend({
		
		_category_helper: null,
		
		_group_helper: null,
		
		_template: $('#group-table'),
		
		_category_views: [],		
		
		events: {
			'click .group-item': function(e){
				Views.GroupContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			} 
		},
			
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			Collections.Categories.getInstance().on('add', $.proxy(function(model){
				this.addCategoryView(new Views.Category({model: model}));
			}, this));
			
			this.render();
		},
		
		render: function(){
			var template = Handlebars.compile(this._template.html());
			this.$el = $(template(this.model.toJSON()));
			this.$el.insertBefore('#groups-hook');
			
			this.delegateEvents();
			return this;
		},
		
		addCategoryView: function(view){
			this._category_views.push(view);
			view.setParent(this).render();
			return this;
		}
	});
});