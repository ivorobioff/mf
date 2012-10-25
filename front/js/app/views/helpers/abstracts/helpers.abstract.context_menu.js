/**
 * Абстрактный хэлпер для контекстного меню
 */
Helpers.Abstract.ContextMenu = Helpers.Abstract.Helper.extend({

	/**
	 * @public
	 */
	doAction: function(e){
		var action =  $(e.target).attr('action');
		
		if (!_.isString(action)){
			return ;
		}
		
		var method = action.toCamelCase();
		
		if (!_.isFunction(this[method])){
			return ;
		}
		
		this[method]();
	}
});