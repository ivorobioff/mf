$(function(){
	Views.Search = Views.Abstract.View.extend({
		
		el: $('#search-bl'),
		
		_helper: null,
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			this._helper = new Helpers.LogsSearchArea(this);
		},
		
		events: {
			'click [name=by_date]': function(){
				var from = this.$el.find('[name=date_from]').val();
				var to = this.$el.find('[name=date_to]').val();
			},
			
			'click [name=by_keyword]': function(){
				var keyword = this.$el.find('[name=keyword]').val();
				Routers.Logs.getInstance().navigate('search/keyword/' + keyword, {context: this});
			},
		}
		
	});
});