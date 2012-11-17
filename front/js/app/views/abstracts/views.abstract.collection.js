Views.Abstract.Collection = Backbone.View.extend({
	
	_view: null,
	_view_instances: null,
	
	instChildren: function(){
		if (this._canInstantiate()){
			
			this._view_instances = new Lib.Collection();
			
			this.collection.forEach(function(model){
				this._view_instances.add(model.id,  new this._view({model: model}));
			}, this);
		}
	},
		
	removeChildren: function(){
		if (!this._view_instances instanceof  Lib.Collection){
			return false;
		}
		
		this._view_instances.clear(function(item){
			item.remove();
		}, this);
	},
	
	reinstChildren: function(){
		this.removeChildren();
		this.instChildren();
	},
	
	_canInstantiate: function(){
		return this._view != null && this.collection instanceof Collections.Abstract.Collection
	}
});