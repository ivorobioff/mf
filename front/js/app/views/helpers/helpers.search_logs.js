Helpers.SearchLogs = Helpers.Abstract.Helper.extend({
	
	_default_params: {
		keyword: '',
		from: '',
		to: ''
	},
	
	prepareParams: function(params){	
		var url = new Lib.Url(params);
		var allowed_params = _.pick(url.toObject(), _.keys(this._default_params)); 
		var params = $.extend(_.clone(this._default_params), allowed_params);
		
		return params;
	}
})