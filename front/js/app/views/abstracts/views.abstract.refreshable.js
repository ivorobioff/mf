$(function(){
	Views.Abstract.Refreshable = Views.Abstract.View.extend({
		initialize: function(){
			
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});