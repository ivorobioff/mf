$(function(){
	Views.Collection.Logs = Views.Abstract.Collection.extend({
		
		_view: Views.Log,
				
		_routes: {
			'get-logs': function(params){
				var helper = new Helpers.SearchLogs(this);							
				this._search(helper.prepareParams(params));
			}
		},
		
		initialize: function(params){
			Views.Abstract.Collection.prototype.initialize.apply(this, arguments);
			
			this.instChildren();
			
			Collections.Logs.getInstance().on('reset', function(){
				this.reinstChildren();
			}, this);
		},
		
		_search: function(params){
			
			Views.Search.getInstance().disableUI();
			
			Lib.Requesty.read({
	
				url: Resources.logs,
				data: params,
				
				success: function(){
					Views.Search.getInstance().enableUI();
				},
				
				error: function(error_handler){
					error_handler.display();
					Views.Search.getInstance().enableUI();
				},
				
				followers: Collections.Logs.getInstance()
			});
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