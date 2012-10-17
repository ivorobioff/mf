$(function(){
	Views.App = Backbone.View.extend({
		
		el: $("html"),
		events: {
			"click body": function(){
				
			}
		}
	});
	
	Views.App._INSTANCE = null;
	Views.App.getInstance = function(){
		
		if (Views.App._INSTANCE == null){
			Views.App._INSTANCE = new Views.App();
		}
		
		return Views.App._INSTANCE;
	}
});
