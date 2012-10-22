Views.Abstract.View = Backbone.View.extend({
	
	_models: null,
	
	initialize: function(){
		this._models = new Lib.Collection();
	},
	
	setModel: function(key, model){
		this._models.add(key, model);
		return this;
	},
	
	getModel: function(key){
		return this._models.get(key);
	},
	
	getDom: function(){
		return this.$el;
	}
});