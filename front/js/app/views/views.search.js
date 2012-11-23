$(function(){
	Views.Search = Views.Abstract.View.extend({
		
		el: $('#search-bl'),
			
		events: {
			'click [name=by_date], [name=by_keyword]': function(){	
				var q = new Lib.Url(this._collectData());
				Lib.Router.getInstance().navigate('?' + q.toString());
			}
		},
		
		_routes: {
			'get-logs': function(params){
				var helper = new Helpers.SearchLogs(this);			
				this.setInputs(helper.prepareParams(params));
			}
		},
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var dp_settings = {
				dateFormat: 'yy-mm-dd',
				duration: 0
			};
			
			this.$el.find('[name=from]').datepicker(dp_settings);
			this.$el.find('[name=to]').datepicker(dp_settings);
		},
		
		setInputs: function(obj){
			for (var i in obj){
				this.$el.find('[name=' + i + ']').val(obj[i]);
			}
		},
		
		disableUI: function(){
			$('[name=by_date], [name=by_keyword]').attr('disabled', 'disabled');
		},
		
		enableUI: function(){
			$('[name=by_date], [name=by_keyword]').removeAttr('disabled');
		},
		
		_collectData: function(){
			var data = {};
			
			this.$el.find('[data-search]').each(function(){	
				var $this = $(this);
				
				if (trim($this.val()) != ''){
					data[$this.attr('name')] = $this.val();
				}
			});
			
			return data;
		}
	});
	
	singleton(Views.Search);
});