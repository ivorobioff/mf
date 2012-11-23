Views.Abstract.Super = Backbone.View.extend({
	
	_routes: null,
	
	initialize: function(){
		Backbone.View.prototype.initialize.apply(this, arguments);
		this._assignRoutes();
	},
	
	_assignRoutes: function(){
		if (_.isObject(this._routes)){
			for (var i in this._routes){
				Lib.Router.getInstance().on('route:' + i, this._routes[i], this);
			}		
		}
	},
});