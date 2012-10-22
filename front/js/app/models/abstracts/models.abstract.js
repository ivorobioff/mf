Models.Abstract.Model = Backbone.Model.extend({
	_views: null,
	
	initialize: function(){
		this._views = new Lib.Collection();
	},
	
	setView: function(key, view){
		this._views.add(key, view);
		return this;
	},
	
	getView: function(key){
		return this._views.get(key);
	}
	
});