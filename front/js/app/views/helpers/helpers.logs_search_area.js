Helpers.LogsSearchArea = Helpers.Abstract.Helper.extend({
	
	searchByDate: function(range){
		
	},
	
	searchByKeyword: function(keyword){
		alert(keyword);
	},
	
	setif: function(key, value, q){
		if (value){
			q.set(key, value);
		} else {
			q.unset(key);
		}
	}
});