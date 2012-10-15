/**
 * Спецификация обозначений:
 * 1. Все названия классов пишутся в стиле CamelCase;
 * 2. Все переменные пишутся в стиле underscore;
 * 3. Все абстрактные классы начинаются с Abs.
 */

/* Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
// Inspired by base2 and Prototype
(function(){
  var initializing = false, fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;

  // The base Class implementation (does nothing)
  this.Class = function(){};
 
  // Create a new Class that inherits from this class
  Class.extend = function(prop) {
    var _super = this.prototype;
   
    // Instantiate a base class (but only create the instance,
    // don't run the init constructor)
    initializing = true;
    var prototype = new this();
    initializing = false;
   
    // Copy the properties over onto the new prototype
    for (var name in prop) {
      // Check if we're overwriting an existing function
      prototype[name] = typeof prop[name] == "function" &&
        typeof _super[name] == "function" && fnTest.test(prop[name]) ?
        (function(name, fn){
          return function() {
            var tmp = this._super;
           
            // Add a new ._super() method that is the same method
            // but on the super-class
            this._super = _super[name];
           
            // The method only need to be bound temporarily, so we
            // remove it when we're done executing
            var ret = fn.apply(this, arguments);       
            this._super = tmp;
           
            return ret;
          };
        })(name, prop[name]) :
        prop[name];
    }
   
    // The dummy class constructor
    function Class() {
      // All construction is actually done in the init method
      if ( !initializing && this.init )
        this.init.apply(this, arguments);
    }
   
    // Populate our constructed prototype object
    Class.prototype = prototype;
   
    // Enforce the constructor to be what we expect
    Class.prototype.constructor = Class;

    // And make this class extendable
    Class.extend = arguments.callee;
   
    return Class;
  };
})();


$(function(){
	/**
	 * Абстрактный класс посредник для категорий и групп 
	 */
	var AbsCategoryHandler = Class.extend({
			
		cmenu: null,
		dom_element: null,
		
		handleCMenu: function(e){
			
			if (this.cmenu == null){
				this.cmenu = new ContextMenu({el: this.dom_element});
				this.cmenu.render();
			}
			
			this.cmenu.show({x: e.pageX, y: e.pageY});
		}
	});
	
	/**
	 * Класс посредник в оперяциях над категориями
	 */
	var CategoryHandler = AbsCategoryHandler.extend({
		dom_element: $("#cm-cats")
	});
	
	/**
	 * Класс посредник в оперяциях над группами категорий
	 */
	var GroupHandler = AbsCategoryHandler.extend({
		dom_element: $("#cm-groups")
	});
	
	/**
	 * Вью таблицы в планнере
	 */
	var PTableView = Backbone.View.extend({
		events: {
			"click .cat-item": function(e){
				category_handler.handleCMenu(e);
			},
			'click .group-item': function(e){
				group_handler.handleCMenu(e);
			} 
		}
	});
	
	/**
	 * Модель таблиц в планнере
	 */
	var PTableModel = Backbone.Model;
	
	/**
	 * Коллексиця моделей таблиц в планнере
	 */
	var PTableCollection = Backbone.Collection.extend({
		model: PTableModel
	});
	
	/**
	 * Вью конетекстного меню
	 */
	var ContextMenu = Backbone.View.extend({
		
		_real_menu: null,
		_is_shown: false,
		_coor: {},
		
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
	
	
	//-----------------------------------------------------
	
	var category_handler = new CategoryHandler();
	var group_handler = new GroupHandler();
	
	$('.count_tb').each(function(){
		new PTableView({el: this});
	});
	
});
