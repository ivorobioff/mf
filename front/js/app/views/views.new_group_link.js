$(function(){
	Views.NewGroupLink = Views.Abstract.View.extend({
		
		el: $('#new-gr'),
	
		events: {
			'click a': function(){
				Views.NewGroupDialog.getInstance().show();
				return false;
			}
		}
	});
});