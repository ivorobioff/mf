$(function(){
	Views.Category = Views.Abstract.Category.extend({
		
		initialize: function(){
			Views.Abstract.Category.prototype.initialize.apply(this, arguments);
			
			this.model.on('change:group_id', $.proxy(function(model){
				this._reRender(Lib.Register.get('group_views').get('group_' + model.get('group_id')));
			}, this));
			
			this.model.on('destroy', $.proxy(function(){
				this.remove();
			}, this));
		},
		
		_reRender: function(parent){
			this.remove();
			this.render(parent);
		}
	});
});