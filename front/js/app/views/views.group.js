/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.View.extend({
		
		_category_handler: null,
		_group_handler: null,
		el: $("#group-table"),
		_category_views: [],		
		
		events: {
			'click .group-item': function(e){
				if (this._group_handler == null){
					this._group_handler = new Handlers.GroupContextMenu();
				}
				this._group_handler.show(e);
				
				return false;
			} 
		},
			
		render: function(){
			var template = Handlebars.compile(this.$el.html());
			this.$el = $(template(this.options.data));
			this.$el.insertBefore("#groups-hook");
	
			for(var i in this._category_views){
				this._category_views[i].render();
			}
			
			this.delegateEvents();
			return this;
		},
		
		attachCategoryViews: function(views){
			for (var i in views){
				views[i].setParent(this);
			}
			
			this._category_views = views;
			return this;
		},
				
		addCategoryView: function(view){
			this._category_views.push(view);
			view.setParent(this).render();
			return this;
		}
	});
});