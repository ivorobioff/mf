/**
 * RemoteRun позволяет с любого места запускать код от имени другого объекта.
 */
Lib.RemoteRun = {
		
	_channels: new Lib.Collection(),
	
	/**
	 * Подключается к объекту через заданный канал и запускает код аннонимной функции
	 * от имении подключенного объекта.
	 * @param String channel - токен канала
	 * @param Function func анонимная функция которая будет запущена от имени подключенного объекта
	 * @param Array params - дополнительные параметры для функции
	 */
	execute: function(channel, func, params){
		var context = this._channels.get(channel);
		
		if (_.isFunction(func)){
			func.apply(context, _.isUndefined(params) ? [] : params);
		}
	},
	
	/**
	 * Создает канал для подключения к объкту
	 * @param String channel - токен канала
	 * @param Object - ссылка на подключаемый объект. 
	 */
	access: function(channel, context){
		this._channels.add(channel, context);
	}
};