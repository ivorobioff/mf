String.prototype.toCamelCase = function(){
	
	var arr = this.split("-");
	var n_str = '';
	
	for (var i in arr){
		n_str += arr[i].charAt(0).toUpperCase() + arr[i].substr(1); 
	}
	
	return n_str.charAt(0).toLowerCase() + n_str.substr(1);
}

Backbone.sync = function(method, model, options) {
	var url = _.isFunction(model.url) ? model.url() : model.url;
	
	var method_map = {
	    "create": "POST",
	    "update": "POST",
	    "delete": "POST",
	    "read":   "GET"
	};
	
	if (!_.has(method_map, method)){
		return false;
	}

	var settings = {
		type: method_map[method],
		url: url,
		data: model.toJSON(),
		dataType: "json",
		success: _.isFunction(options.success) ? options.success : function(){},
		error: _.isFunction(options.error) ? options.error : function(){},
	}
	
	return $.ajax(settings);
};