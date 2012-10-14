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
			
			if (this.cmenu.isShown()){
				this.cmenu.hide();
			} else {
				this.cmenu.show();
			}
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
		
		_real_menu: null,
		_is_shown: false,
		
		render: function(){
			this._real_menu = $(this.$el.html());
			$('body').append(this._real_menu);
		},
		
		show: function(){
			this._real_menu.show();
			this._is_shown = true;
		},
		
		hide: function(){
			this._real_menu.hide();
			this._is_shown = false;
		},
		
		getDomMenu: function(){
			return this._real_menu;
		},
		
		isShown: function(){
			return this._is_shown;
		}
	});	
		
	//-----------------------------------------------------
	
	$('.count_tb').each(function(){
		new ManipulatorTable({el: this});
	});
	
});
