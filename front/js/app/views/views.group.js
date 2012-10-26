/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.View.extend({
		
		_template: $('#group-table'),
	
		events: {
			'click .group-item': function(e){
				Views.GroupContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			} 
		},
			
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			Collections.Categories.getInstance().on('add', $.proxy(function(model){
				if (model.get('group_id') == this.model.id){
					new Views.Category({model: model}).render(this);
				}
			}, this));
			
			this.render();
			
			Lib.Register.get('group_views').add('group_' + this.model.id, this);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
		},
		
		render: function(){
			var template = Handlebars.compile(this._template.html());
			this.$el = $(template(this.model.toJSON()));
			this.$el.insertBefore('#groups-hook');
			
			this.delegateEvents();
			return this;
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});