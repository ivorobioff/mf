/**
 * Класс для обработки ошибок полученных с сервера
 */
Lib.ErrorHandler = Class.extend({
	
	_error_data: null,
	
	initialize: function (data){
		this._error_data = data;
	},
	
	/**
	 * Показывает ошибки
	 */
	display: function(){
		var message = "";
		var n = "";
		for (var i in this._error_data){
			message +=n + this._error_data[i];
			n = "\n";
		}
		
		alert(message);
	}
	
});