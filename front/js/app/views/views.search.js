$(function(){
	Views.Search = Views.Abstract.View.extend({
		el: $('#search-bl'),
		
		events: {
			'click input': function(){
				alert('asdf');
			}
		}
		
	});
});