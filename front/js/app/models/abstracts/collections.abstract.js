Collections.Abstract.Collection = Backbone.Collection.extend({
	/*_is_ok: false,
	
	isOk: function(){
		return this._is_ok;
	},
	
	parse: function(r){
		this._is_ok = (r.status == "ok");
			
		if (!_.isArray(r.data)){
			throw "Collection must be an array.";
		}
		
		for (var i in r.data){
			r.data[i] = {status: r.status, data: r.data[i]};
		}
		
		return r.data;
	}*/
});