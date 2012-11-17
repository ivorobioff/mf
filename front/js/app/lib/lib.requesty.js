/**
 * Либа для отправки независимых от моделей ajax запросов и обновления моделей после успешного ответа.
 */
Lib.Requesty = {
	
	_request_types: {
		'create': 'post',
		'update': 'post',
		'read': 'post',
		'delete': 'post',
		'post': 'post',
		'get': 'get'
	},
	
	_method: null,
	
	_options_clean: {
		'data': {},
		'id': null,
		'success': function(){},
		'error': function(){},
		'followers': {
			'update_models': {},
			'delete_models': []
		},
		'url': ''
	},
	
	_options: null,
	
	_followers_structure: null,
	
	create: function(options){
		this._method = 'create';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	update: function(options){
		this._method = 'update';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	read: function(options){
		this._method = 'read';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	remove: function(options){
		this._method = 'delete';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	post: function(options){
		this._method = 'post';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	get: function(options){
		this._method = 'get';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	_makeRequest: function(){
		this._prepare()._send();
	},
	
	_assignOptions: function(options){
		this._options = _.extend(_.clone(this._options_clean), options);
	},
	
	_prepare: function(){
			
		this._followers_structure = this._options.followers;
		
		if (this._options.data instanceof Models.Abstract.Model){
			this._options.data = this._options.data.toJSON();
		}
		
		if (!_.isNull(this._options.id) && !_.isUndefined(this._options.id)){
			this._options.data.id = this._options.id;
		}
		
		this._options.url = this._options.url.replace('{method}', this._method); 
		
		if(!_.has(this._options.followers, 'update_models')
				&& !_.has(this._options.followers, 'delete_models')){
			
			if (this._method == 'delete'){
				this._options.followers = {delete_models: this._options.followers}
			} else {
				this._options.followers = {update_models: this._options.followers}
			}
		}
		
		if (this._isModel(this._options.followers.update_models)){
			this._options.followers.update_models = {'def': this._options.followers.update_models};
		}
		
		if (this._isModel(this._options.followers.delete_models)){
			this._options.followers.delete_models = [this._options.followers.delete_models];
		}
		
		if (_.isUndefined(this._options.followers.update_models)){
			this._options.followers.update_models = {};
		}
		
		if (_.isUndefined(this._options.followers.delete_models)){
			this._options.followers.delete_models = [];
		}
		
		return this;
	},
	
	_isModel: function(e){
		return e instanceof Models.Abstract.Model
		|| e instanceof Collections.Abstract.Collection
	},
	
	_send: function(){
		
		$.ajax({
			type: this._request_types[this._method],
			url: this._options.url,
			data: this._options.data,
			dataType: 'json',
			success: $.proxy(function(data){
					
				var updates = this._options.followers.update_models;
				var deletes = this._options.followers.delete_models;
				
				if (_.isObject(data)){
					for (var i in data){
						if (updates[i] instanceof Models.Abstract.Model){
							updates[i].set(data[i]);
							continue ;
						}
						
						if (updates[i] instanceof Collections.Abstract.Collection){
							updates[i].reset(data[i]);
							continue ;
						}
					}
				}
				
				for (var i in deletes){
					if (deletes[i] instanceof Models.Abstract.Model){
						deletes[i].destroy();
						continue ;
					}
					
					if (deletes[i] instanceof Collections.Abstract.Collection){
						deletes[i].clear();
						continue ;
					}
					
				}
				
				if (_.isFunction(this._options.success)){
					this._options.success(this._followers_structure, data);
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
					
					if( _.isFunction(this._options.error)){
						this._options.error(new Lib.ErrorHandler(jdata), this._options.followers);
						return ;
					}
				}
				
				throw 'Response error: ' + data.responseText;
			}, this)
		});
	}
	
}