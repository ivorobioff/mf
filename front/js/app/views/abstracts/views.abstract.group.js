/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Abstract.Group = Views.Abstract.View.extend({
		
		_template: $('#group-table'),
			
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var template = Handlebars.compile(this._template.html());
			this.$el = $(template(this.model.toJSON()));
			this.$el.insertBefore('#groups-hook');
			
			this.delegateEvents();
			return this;
		}
	});
});