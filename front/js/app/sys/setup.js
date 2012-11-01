//Добавил метод в объект String
String.prototype.toCamelCase = function(){
	
	var arr = this.split('-');
	var n_str = '';
	
	for (var i in arr){
		n_str += arr[i].charAt(0).toUpperCase() + arr[i].substr(1); 
	}
	
	return n_str.charAt(0).toLowerCase() + n_str.substr(1);
}

/**
 * Полезная функция для дебага. Выводит хэш атрибутов объекта
 */
function pred(data){
	alert(JSON.stringify(data));
}


//Переопределил Backbone.sync
Backbone.sync = function(method, model, options) {/*
	var url = _.isFunction(model.url) ? model.url() : model.url;
	
	var method_map = {
	    'create': 'POST',
	    'update': 'POST',
	    'delete': 'POST',
	    'read':   'GET'
	};
	
	if (!_.has(method_map, method)){
		return false;
	}

	url = url.replace('{method}', method);
	
	if (!_.isUndefined(options.url)){
		url = options.url;
	}
	
	var settings = {
		type: method_map[method],
		url: url,
		data: model.toJSON(),
		dataType: 'json',
		success: _.isFunction(options.success) ? options.success : function(){},
		error: function(data){
			if (data.status == '403'){
				var jdata;
				try{
					jdata = $.parseJSON(data.responseText);					
				} catch(e) {
					throw 'Response error: ' + data.responseText;
				}
				
				if( _.isFunction(options.error)){
					options.error(new Lib.ErrorHandler(jdata));
					return ;
				}
			}
			
			throw 'Response error: ' + data.responseText;
		}
	}
	
	return $.ajax(settings);*/
};

/**
 * Соберает данны с формы для сабмита
 */
$.fn.dataForSubmit = function(){
	var data = {};
	this.find('[data-submit]').each(function(e){
		var $this = $(this);
		var $val = $this.val();
		
		if (($this.attr('type') == 'checkbox' || $this.attr('type') == 'radio') && !$this.attr('checked')){
			$val = 0;
		}

		data[$this.attr('name')] = $val;
	});
	
	return data;
};

/**
 * обновляет данные в елементах которые имеют атрибут data-field 
 */
$.fn.refreshDataFields = function(data){

	for (var i in data){
		this.find('[data-field="' + i + '"]').html(_.escape(data[i]));
	}
	
	return data;
};
