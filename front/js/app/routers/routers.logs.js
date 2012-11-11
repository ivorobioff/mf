Routers.Logs = Routers.Abstract.Router.extend({
	_routes: {
		'search/keyword/:keyword': function(keyword){
			alert(keyword);
		},
	},
});

appendSingleton(Routers.Logs);