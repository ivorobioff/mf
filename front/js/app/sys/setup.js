//Добавил метод в объект String
String.prototype.toCamelCase = function(){
	
	var arr = this.split("-");
	var n_str = '';
	
	for (var i in arr){
		n_str += arr[i].charAt(0).toUpperCase() + arr[i].substr(1); 
	}
	
	return n_str.charAt(0).toLowerCase() + n_str.substr(1);
}

//Переопределил Backbone.sync
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

	url = url.replace("{method}", method);
	
	var settings = {
		type: method_map[method],
		url: url,
		data: model.toJSON(),
		dataType: "json",
		success: _.isFunction(options.success) ? options.success : function(){},
		error: function(data){
			if (data.status == "403"){
				try{
					var jdata = $.parseJSON(data.responseText);
					
					if( _.isFunction(options.error)){
						options.error(jdata);
						return ;
					}
					
				} catch(e) {
					throw "Response error: " + data.responseText + " : " + data.status;
				}
			}
			
			throw "Response error: " + data.responseText + " : " + data.status;
		}
	}
	
	return $.ajax(settings);
};

$.fn.dataForSubmit = function(){
	var data = {};
	this.find("[data-submit]").each(function(e){
		data[$(this).attr("name")] = $(this).val();
	});
	
	return data;
};
