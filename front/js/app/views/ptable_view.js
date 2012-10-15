/**
 * Вью таблицы в планнере
 */
var PTableView = Backbone.View.extend({
	events: {
		"click .cat-item": function(e){
			category_handler.handleCMenu(e);
		},
		'click .group-item': function(e){
			group_handler.handleCMenu(e);
		} 
	}
});