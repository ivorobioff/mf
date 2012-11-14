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
	
	navigate: function($url, context, params){
		
		if (_.isUndefined(params)){
			params = {};
		}
		
		this._context = !_.isUndefined(context) ? context : null;
		
		params.trigger = true;
		
		Backbone.Router.prototype.navigate.apply(this, arguments);
	},
});