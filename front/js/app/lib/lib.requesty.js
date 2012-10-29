/**
 * Либа для отправки независимых от моделей ajax запросов и обновления моделей после успешного ответа.
 */
Lib.Requesty = Class.extend({
	
	_followers: null,
	
	initialize: function(){
		this._followers = {};
	},
	
	post: function(options){
		this._request('POST', options);
	},
	
	get: function(options){
		this._request('GET', options);
	},
	
	_request: function(type, options){
		
		if (_.isObject(options.followers)){
			this._followers = options.followers;
		}
		
		$.ajax({
			type: type,
			url: options.url,
			data: _.isObject(options.data) ? options.data : {},
			dataType: 'json',
			success: $.proxy(function(data){
				
				if (!_.isObject(data)){
					return false;
				}
				
				for (var i in data){
					if (this._followers[i] instanceof Backbone.Model){
						this._followers[i].set(data[i]);
					}
				}
				
				if (_.isFunction(options.success)){
					options.success(this._followers, data);
				}
			}, this),
			
			error: $.proxy(function(data){
				if (data.status == '403'){
					
					var jdata;
					
					try{
						jdata = $.parseJSON(data.responseText);					
					} catch(e) {
						throw 'Response error: ' + data.responseText;
					}
					
					if( _.isFunction(options.error)){
						options.error(this._followers, new Lib.ErrorHandler(jdata));
						return ;
					}
				}
				
				throw 'Response error: ' + data.responseText;
			}, this)
		});
	},
	
});