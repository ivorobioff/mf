/**
 * Класс для работы с url
 */
Lib.Url = Class.extend({
	
	_object: null,
	
	initialize: function(obj){
			
		if (typeof obj === 'undefined'){
			obj = {};
		}
		
		if (typeof obj === 'string'){
			obj = obj.trim('?');
			obj = this._parseString(obj);
		}
		
		this._object = obj;
	},
	
	get: function(field){
		
		if (typeof this._object[field] === 'undefined'){
			return this._object[field];
		}
		
		return this._object[field];
	},
	
	set: function(field, value){
		this._object[field] = value;
	},
	
	unset: function(field){
		delete this._object[field];
	},
	
	toString: function(){
		return this._buildQuery(this._object);
	},
	
	toObject: function(){
		return this._object;
	},
	
	_parseString: function(str){
		
		if (typeof str !== 'string' || str == '' || str.indexOf('=') == -1){
			return {};
		}
		
		var pairs = str.split('&');
		var res = {};
		
		for (var i in pairs){
			
			if (pairs[i].indexOf('=') == -1){
				continue ;
			}
			
			var pair = pairs[i].split('=');
			res[pair[0]] = decodeURIComponent(pair[1]);
		}
		
		return res;
	},
	
	_buildQuery: function(){
		var str = '';
		var d = '';
		
		for (var i in this._object){
			str += d + i + '=' + encodeURIComponent(this._object[i]);
			d = '&';
		}
		
		return str;
	}
});