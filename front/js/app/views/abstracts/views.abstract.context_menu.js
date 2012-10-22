/**
 * Вью конетекстного меню
 */
$(function(){
	Views.Abstract.ContextMenu = Views.Abstract.View.extend({
		
		_is_shown: false,
		_coor: {},
		
		_context_menu_helper: null,
		
		events:{
			"click a": function(e){
				
				this._context_menu_helper.doAction(e);
				
				return false;
			}
		},
		
		initialize: function(){	
			this.render();
			
			this.$el.mousedown(function(){
				return false;
			});
			
			Lib.Eventor.getInstance().on("click:body", function(){
				if (this.isShown()){
					this.hide();
				}
			}, this);
		},
		
		render: function(){
			this.$el = $(this.$el.html());
			$('body').append(this.$el);
		},
		
		show: function(coor){
			this._coor = coor; 
			this.$el.show();
			this._setPosition();
			this._is_shown = true;
		},
		
		hide: function(){
			this.$el.hide();
			this._is_shown = false;
		},
				
		isShown: function(){
			return this._is_shown;
		},
		
		_setPosition: function(){
			this.$el.css({left: this._coor.x, top: this._coor.y});
		},
	});
});