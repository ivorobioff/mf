/**
 * Класс для обработки контекстного меню групп
 */
$(function(){
	Handlers.GroupContextMenu = Handlers.Interface.ContextMenu.extend({
		
		_groups_collection: null,
		
		show: function(e){
			Views.GroupCMenu.getInstance().show({x: e.pageX, y: e.pageY});
		},
		
		doAction: function(e){
			var action =  $(e.target).attr("action");
			
			if (!_.isString(action)){
				return ;
			}
			
			var method = action.toCamelCase();
			
			if (!_.isFunction(this[method])){
				return ;
			}
			
			this[method]();
		},
	
		addCategory: function(){
			if (_.isNull(this._groups_collection)){
				this._groups_collection = Collections.Groups.getInstance();
			}
			
			Views.NewCatDialog.getInstance().setData(Collections.Groups.getInstance().toJSON()).show();
			Views.GroupCMenu.getInstance().hide();
		},
	});
});