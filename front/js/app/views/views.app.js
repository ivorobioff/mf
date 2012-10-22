$(function(){
	Views.App = Views.Abstract.View.extend({
		
		el: $("html"),
		
		events: {
			"mousedown body": function(){
				Lib.Eventor.getInstance().trigger("click:body")
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
