Routers.Logs = Routers.Abstract.Router.extend({
	
	_helper: null,
	
	_routes: {
		'?*params': function(params){
			var url = new Lib.Url(params);
			var keyword = url.get('keyword');
			
			if (typeof keyword === 'undefined' || keyword.trim() == ''){
				return false
			}
			
			this._helper.searchByKeyword(keyword);
			
		}
	},
	
	initialize: function(){
		Routers.Abstract.Router.prototype.initialize.apply(this, arguments);
		
		this._helper = new Helpers.LogsSearchArea(this._context);
	}
});

appendSingleton(Routers.Logs);