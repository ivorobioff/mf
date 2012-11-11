Routers.Abstract.Router = Backbone.Router.extend({
	
	_routes: {},
	
	_context: null,
	
	_setRoutes: function(){
		var c = 0;
		
		for (var i in this._routes){
			var ns = 'ns_' + c;
			this.route(i, ns, this._routes[i]);
			c++;
		}
	},
	
	initialize: function(){
		this._setRoutes();
	},
	
	navigate: function($url, params){
		
		this._context = !_.isUndefined(params.context) ? params.context : null;
		
		params.trigger = true;
		
		Backbone.Router.prototype.navigate.apply(this, arguments);
	},
});