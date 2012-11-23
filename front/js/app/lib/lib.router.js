Lib.Router = Backbone.Router.extend({
	
	routes: Routes,
	
	navigate: function(url){
		Backbone.Router.prototype.navigate.apply(this, [url, {trigger: true}]);
	},
});

singleton(Lib.Router);