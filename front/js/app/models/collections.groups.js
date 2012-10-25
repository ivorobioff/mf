Collections.Groups = Collections.Abstract.Collection.extend({
	model: Models.Group
});

Collections.Groups._INSTANCE = null;

Collections.Groups.getInstance = function(){	
	
	if (Collections.Groups._INSTANCE == null){
		Collections.Groups._INSTANCE = new Collections.Groups(DataSource.Groups);
	}
	
	return Collections.Groups._INSTANCE;
}