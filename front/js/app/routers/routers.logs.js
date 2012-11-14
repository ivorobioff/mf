Routers.Logs = Routers.Abstract.Router.extend({
	_routes: {
		'?*params': function(params){
			//alert(params);
		}
	},
});

appendSingleton(Routers.Logs);