$(function(){
	Views.CategoryCMenu = Views.Abstract.ContextMenu.extend({
		initialize: function(){
			if (!Views.CategoryCMenu.IS_RENDERED)
			{
				this.render();
				Views.CategoryCMenu.IS_RENDERED = true;
			}
		}
	});
})