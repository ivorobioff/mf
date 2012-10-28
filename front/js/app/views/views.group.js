/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.Group.extend({
		
		events: {
			'click .group-item': function(e){
				Views.GroupContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			} 
		},
			
		initialize: function(){
			Views.Abstract.Group.prototype.initialize.apply(this, arguments);
			
			Collections.Categories.getInstance().on('add', $.proxy(function(model){
				if (model.get('group_id') == this.model.id){
					new Views.Category({model: model}).render(this);
				}
			}, this));
			
			Lib.Register.get('group_views').add('group_' + this.model.id, this);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
			
			this.model.on('destroy', $.proxy(function(){
				this.remove();
			}, this));
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});