Helpers.LogsSearchArea = Helpers.Abstract.Helper.extend({
	
	searchByDate: function(range){
		pred(range);
	},
	
	searchByKeyword: function(keyword){
		Collections.Logs.getInstance().clear();
	},
	
	setif: function(key, value, q){
		if (value){
			q.set(key, value);
		} else {
			q.unset(key);
		}
	}
});