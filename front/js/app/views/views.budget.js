$(function(){
	Views.Budget = Views.Abstract.Refreshable.extend({
				
		_budget_menu: null,
		
		events: {
			'click #menu a': function(e){
				
				if (this._budget_menu == null){
					this._budget_menu = new Helpers.BudgetMenu(this);
				}
				
				this._budget_menu.doAction(e);
				
				return false;
			}
		},
		
		initialize: function(){
			Views.Abstract.Refreshable.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var template =  Handlebars.compile(this.$el.html());
			this.$el.html(template(this.model.toJSON()));
		}
	});
});