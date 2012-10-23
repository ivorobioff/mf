/**
 * Паттерн регистратор
 */
Lib.Register = {};

Lib.Register._data = {};

Lib.Register.add = function(key, value){
	var data = {};
	data[key] = value;
	$.extend(Lib.Register._data, data);
}

Lib.Register.get = function(key){
	return Lib.Register._data[key];
}