$(function(){		
	
	var app = Views.App.getInstance();
	
	$('.count_tb').each(function(){
		new Views.PTable({el: this});
	});
});
