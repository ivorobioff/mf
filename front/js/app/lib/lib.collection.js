Lib.Collection = Class.extend({
	_data: {},
	
	add: function(key, value){
		var data = {};
		data[key] = value;
		$.extend(this._data, data);
		return this;
	},
	
	get: function(key){
		return this._data[key];
	}
});