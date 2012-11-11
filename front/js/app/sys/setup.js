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

/**
 * Создает синглтон для класа
 * @param class_name
 */
function appendSingleton(class_name){
	class_name._INSTANCE = null;
	
	class_name.getInstance = function(){
		
		if (class_name._INSTANCE == null){
			class_name._INSTANCE = new class_name();
		}
		
		return class_name._INSTANCE;
	}
}

//Переопределил Backbone.sync
Backbone.sync = function(method, model, options) {};

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
