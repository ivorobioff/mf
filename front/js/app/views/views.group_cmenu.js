$(function(){
	Views.GroupCMenu = Views.Abstract.ContextMenu.extend({
		initialize: function(){
			if (!Views.GroupCMenu.IS_RENDERED)
			{
				this.render();
				Views.CategoryCMenu.IS_RENDERED = true;
			}
		}
	});
});