/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.PTable = Backbone.View.extend({
		
		_category_handler: null,
		_group_handler: null,
		
		events: {
			"click .cat-item": function(e){
				if (this._category_handler == null){
					this._category_handler = new Handlers.CategoryContextMenu();
				}
				
				this._category_handler.show(e);
				return false;
			},
			'click .group-item': function(e){
				if (this._group_handler == null){
					this._group_handler = new Handlers.GroupContextMenu();
				}
				this._group_handler.show(e);
				return false;
			} 
		}
	});
});