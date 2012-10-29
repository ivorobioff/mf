Views.Abstract.View = Backbone.View.extend({
	
	_models: null,
	_params: null,
	
	initialize: function(){
		this._models = new Lib.Collection();
		this._params = new Lib.Collection();
	},
	
	addModel: function(key, model){
		this._models.add(key, model);
		return this;
	},
	
	getModel: function(key){
		return this._models.get(key);
	},
	
	getDom: function(){
		return this.$el;
	},
	
	/**
	 * Позволяет прикрепить вьюшке дополнительные параметры
	 */
	assign: function (key, value){
		
		this._params.add(key, value);
		
		return this;
	},
	
	/**
	 * получает прикрепленный параметр
	 */
	getParam: function(key){
		return this._params.get(key);
	}
});