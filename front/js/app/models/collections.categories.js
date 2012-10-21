Collections.Categories = Collections.Abstract.Collection.extend({
	model: Models.Category,
	url: Resources.categories,
	
	initialize: function(){
		Collections.Abstract.Collection.prototype.initialize.apply(this, arguments);
		
		this.on("add", function(model){
			Lib.Register.get("group_views")
				.get(model.get("group_id"))
				.addCategoryView(new Views.Category({data: model.toJSON()}));
		});
	}
	
});

Collections.Categories._INSTANCE = null;

Collections.Categories.getInstance = function(){
	
	if (Collections.Categories._INSTANCE == null){
		Collections.Categories._INSTANCE = new Collections.Categories(DataSource.Categories);
	}
	
	return Collections.Categories._INSTANCE;
}