$(function(){
	Views.Collection.Logs = Views.Abstract.Collection.extend({
		_view: Views.Log,
		
		initialize: function(params){
			Views.Abstract.Collection.prototype.initialize.apply(this, arguments);
			
			this.instChildren();
			
			Collections.Logs.getInstance().on('reset', function(){
				this.reinstChildren();
			}, this);
			
			Models.Budget.getInstance().on('change', function(){
				
			}, this);
		}
	});
	
	Views.Collection.Logs._INSTANCE = null;
	Views.Collection.Logs.getInstance = function(){
		
		if (Views.Collection.Logs._INSTANCE == null){
			Views.Collection.Logs._INSTANCE = new Views.Collection.Logs({collection: Collections.Logs.getInstance()});
		}
		
		return Views.Collection.Logs._INSTANCE;
	}
});