$(function(){
	
	/**
	 * Объект посредник между вьюшками и моделями в оперяциях над категориями
	 */
	var category_handler = {
			
		cmenu: null,
		
		handleCMenu: function($el){
			
			if (this.cmenu == null){
				this.cmenu = new ContextMenu({el: $("#context-menu")});
				this.cmenu.render();
			}
			
			this.cmenu.show();
		}
	};
	
	/**
	 * Вью таблицы в планнере
	 */
	var ManipulatorTable = Backbone.View.extend({
		events: {
			"click .tab-menu": function(e){
				category_handler.handleCMenu($(e.target));
			}
		}
	});
	
	/**
	 * Вью конетекстного меню
	 */
	var ContextMenu = Backbone.View.extend({
		render: function(){
			this.$el.
		},
		
		show: function(){
			
		},
		
		hide: function(){
			
		}
	});	
		
	//-----------------------------------------------------
	
	$('.count_tb').each(function(){
		new ManipulatorTable({el: this});
	});
	
});
