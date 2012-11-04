Models.Budget = Models.Abstract.Model.extend({
	
});

Models.Budget._INSTANCE == null;

Models.Budget.getInstance = function(){
	if (Models.Budget._INSTANCE == null){
		Models.Budget._INSTANCE = new Models.Budget(DataSource.budget);
	}
	
	return Models.Budget._INSTANCE;
}