$(function(){
	Views.Search = Views.Abstract.View.extend({
		
		el: $('#search-bl'),
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
		},
		
		events: {
			'click [name=by_date], [name=by_keyword]': function(){	
				var q = new Lib.Url(this._collectData());
				Routers.LogsSearch.getInstance().navigate('?' + q.toString());
			}
		},
		
		setInputs: function(obj){
			for (var i in obj){
				this.$el.find('[name=' + i + ']').val(obj[i]);
			}
		},
		
		_collectData: function(){
			var data = {};
			
			this.$el.find('[data-search]').each(function(){	
				var $this = $(this);
				
				if ($this.val().trim() != ''){
					data[$this.attr('name')] = $this.val();
				}
			});
			
			return data;
		}
	});
	
	singleton(Views.Search);
});