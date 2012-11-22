Lib.Router = Backbone.Router.extend({
	
	_routes: null,
	_context: null,
	
	_setRoutes: function(){
		var c = 0;
		
		for (var i in this._routes){
			var ns = 'ns_' + c;
			this.route(i, ns, $.proxy(this._routes[i], this._context));
			c++;
		}
	},
	
	initialize: function(routes, context){
		this._routes = routes;
		this._context = context;
		this._setRoutes();
	},
	
	navigate: function(url){
		Backbone.Router.prototype.navigate.apply(this, [url, {trigger: true}]);
	},
});