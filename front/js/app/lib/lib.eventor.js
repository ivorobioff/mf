Lib.Eventor = Class.extend(Backbone.Events);
Lib.Eventor._INSTANCE = null;

Lib.Eventor.getInstance = function(){
	
	if (Lib.Eventor._INSTANCE == null){
		Lib.Eventor._INSTANCE = new Lib.Eventor();
	}
	
	return Lib.Eventor._INSTANCE;
};