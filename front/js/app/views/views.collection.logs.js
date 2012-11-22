$(function(){
	Views.Collection.Logs = Views.Abstract.Collection.extend({
		
		_view: Views.Log,
		
		_default_params: {
			keyword: '',
			from: '',
			to: ''
		},
		
		_routes: {
			'*params': function(params){
				var url = new Lib.Url(params);
				var allowed_params = _.pick(url.toObject(), _.keys(this._default_params)); 
				var params = $.extend(_.clone(this._default_params), allowed_params);
				
				Views.Search.getInstance().setInputs(params);
				
				this._search(params);
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
			Lib.Requesty.read({
				
				url: Resources.logs,
				data: params,
				
				success: function(){
					
				},
				
				error: function(error_handler){
					error_handler.display();
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