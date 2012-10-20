Models.Abstract.Model = Backbone.Model.extend({
	
	_is_ok: false,

	isOk: function(){
		return this._is_ok;
	},
	
	parse: function(r){
		this._is_ok = (r.status == "ok");
		
		return r.data;
	}
});