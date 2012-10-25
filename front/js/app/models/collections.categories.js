Collections.Categories = Collections.Abstract.Collection.extend({
	model: Models.Category,
	url: Resources.categories
});

Collections.Categories._INSTANCE = null;

Collections.Categories.getInstance = function(){
	
	if (Collections.Categories._INSTANCE == null){
		Collections.Categories._INSTANCE = new Collections.Categories(DataSource.Categories);
	}
	
	return Collections.Categories._INSTANCE;
}