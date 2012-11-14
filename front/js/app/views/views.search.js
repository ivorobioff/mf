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
				var from = this.$el.find('[name=date_from]').val().trim();
				var to = this.$el.find('[name=date_to]').val().trim();
			
				var q = new Lib.Url(location.hash.trim('#'));
				
				this._helper.setif('from', from, q);
				this._helper.setif('to', to, q);
				
				Routers.Logs.getInstance().navigate('?' + q.toString(), this);
			},
			
			'click [name=by_keyword]': function(){
				var keyword = this.$el.find('[name=keyword]').val().trim();
				
				var q = new Lib.Url(location.hash.trim('#'));
				
				this._helper.setif('keyword', keyword, q);
		
				Routers.Logs.getInstance().navigate('?' + q.toString(), this);				
			},
		},
		
	});
});