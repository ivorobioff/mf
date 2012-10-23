Collections.Abstract.Collection = Backbone.Collection.extend({
	
	/**
	 * ищет модель в коллекции по заданному условию и возвращает реальные объекты модели
	 * @public
	 */
	search: function(data){
		
		var res = this.where(data);
		var _return = [];
		
		if (res.length == 0){
			return _return;
		}
		
		for (var i in res){
			_return.push(this.get(res[i].id));
		}
		
		return _return;
	},
	
	/**
	 * ищет модель в коллекции по заданному условию и возвращает реальный объект модели
	 * @public
	 */
	searchOne: function(data){
		var res = this.search(data);
		
		if (res.length == 0 ){
			return res;
		}
		
		return res.shift();
	}
	
});