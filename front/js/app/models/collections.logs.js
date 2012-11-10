Collections.Logs = Collections.Abstract.Collection.extend({
	model: Models.Log
});

Collections.Logs._INSTANCE = null;

Collections.Logs.getInstance = function(){
	
	if (Collections.Logs._INSTANCE == null){
		Collections.Logs._INSTANCE = new Collections.Logs(DataSource.Logs);
	}
	
	return Collections.Logs._INSTANCE;
}