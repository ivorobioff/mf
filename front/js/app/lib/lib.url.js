Lib.Url = Class.extend({
	
	_object: null,
	_flq: null,
	
	initialize: function(obj){
			
		this._flq = new FLQ.URL();
		
		if (typeof obj === 'undefined'){
			obj = {};
		}
		
		if (typeof obj === 'string'){
			obj = obj.trim('?');
			obj = this._flq.parseArgs(obj);
		}
		
		this._object = obj;
	},
	
	get: function(field){
		return this._object[field];
	},
	
	set: function(field, value){
		this._object[field] = value;
	},
	
	unset: function(field){
		delete this._object[field];
	},
	
	toString: function(){
		return this._flq.toArgs(this._object);
	},
	
	toObject: function(){
		return this._object;
	}
});