Routers.LogsSearch = Routers.Abstract.Router.extend({
	
	_default_params: {
		keyword: '',
		from: '',
		to: ''
	},

	_routes: {
		'*params': function(params){
			var url = new Lib.Url(params);
			var allowed_params = _.pick(url.toObject(), _.keys(this._default_params)); 
			var params = $.extend(_.clone(this._default_params), allowed_params);
			
			Views.Search.getInstance().setInputs(params);
			
			this._search(params);
		}
	},
	
	_search: function(params){
		Lib.Requesty.read({
			
			url: Resources.logs,
			data: params,
			
			success: function(){
				
			},
			
			error: function(error_handler){
				error_handler.display();
			},
			
			followers: Collections.Logs.getInstance()
		});
	}
});

singleton(Routers.LogsSearch);