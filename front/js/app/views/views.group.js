/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.View.extend({
		
		_category_helper: null,
		
		_group_helper: null,
		
		el: $('#group-table'),
		
		_category_views: [],		
		
		events: {
			'click .group-item': function(e){
				Views.GroupContextMenu.getInstance().show({x: e.pageX, y: e.pageY});
				return false;
			} 
		},
			
		render: function(){
			var template = Handlebars.compile(this.$el.html());
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