/**
 * Вью конетекстного меню
 */
$(function(){
	Views.Abstract.ContextMenu = Backbone.View.extend({
		
		_real_menu: null,
		_is_shown: false,
		_coor: {},
		
		initialize: function(){			
			this.render();
			
			this._real_menu.click(function(){
				return false;
			});
			
			Lib.Eventor.getInstance().on("click:body", function(){
				if (this.isShown()){
					this.hide();
				}
			}, this);
		},
		
		render: function(){
			this._real_menu = $(this.$el.html());
			$('body').append(this._real_menu);
		},
		
		show: function(coor){
			this._coor = coor; 
			this._real_menu.show();
			this._setPosition();
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
		},
		
		_setPosition: function(){
			this._real_menu.css({left: this._coor.x, top: this._coor.y});
		}
	});

	Views.Abstract.ContextMenu._INSTANCE = null;
	Views.Abstract.ContextMenu.getInstance = function(){};
});