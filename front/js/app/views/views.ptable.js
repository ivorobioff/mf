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
					this._category_handler = new Handlers.Category();
				}
				
				this._category_handler.showCMenu(e);
				return false;
			},
			'click .group-item': function(e){
				if (this._group_handler == null){
					this._group_handler = new Handlers.Group();
				}
				this._group_handler.showCMenu(e);
				return false;
			} 
		}
	});
});