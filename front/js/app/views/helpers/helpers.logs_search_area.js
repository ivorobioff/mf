Helpers.LogsSearchArea = Helpers.Abstract.Helper.extend({
	
	searchByDate: function(range){
		pred(range);
	},
	
	searchByKeyword: function(keyword){
		Collections.Logs.getInstance().clear();
	}	
});