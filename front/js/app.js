(function(){var initializing=false,fnTest=/xyz/.test(function(){xyz;})?/\b_super\b/:/.*/;this.Class=function(){};Class.extend=function(prop){var _super=this.prototype;initializing=true;var prototype=new this();initializing=false;for(var name in prop){prototype[name]=typeof prop[name]=="function"&&typeof _super[name]=="function"&&fnTest.test(prop[name])?(function(name,fn){return function(){var tmp=this._super;this._super=_super[name];var ret=fn.apply(this,arguments);this._super=tmp;return ret;};})(name,prop[name]):prop[name];}
function Class(){if(!initializing&&this.init)
this.init.apply(this,arguments);}
Class.prototype=prototype;Class.prototype.constructor=Class;Class.extend=arguments.callee;return Class;};})();
var ContextMenu=Backbone.View.extend({_real_menu:null,_is_shown:false,_coor:{},render:function(){this._real_menu=$(this.$el.html());$('body').append(this._real_menu);},show:function(coor){this._coor=coor;this._real_menu.show();this._setPosition();this._is_shown=true;},hide:function(){this._real_menu.hide();this._is_shown=false;},getDomMenu:function(){return this._real_menu;},isShown:function(){return this._is_shown;},_setPosition:function(){this._real_menu.css({left:this._coor.x,top:this._coor.y});}});
var PTableView=Backbone.View.extend({events:{"click .cat-item":function(e){category_handler.handleCMenu(e);},'click .group-item':function(e){group_handler.handleCMenu(e);}}});
var AbsCategoryHandler=Class.extend({cmenu:null,dom_element:null,handleCMenu:function(e){if(this.cmenu==null){this.cmenu=new ContextMenu({el:this.dom_element});this.cmenu.render();}
this.cmenu.show({x:e.pageX,y:e.pageY});}});
var CategoryHandler=AbsCategoryHandler.extend({dom_element:$("#cm-cats")});
var GroupHandler=AbsCategoryHandler.extend({dom_element:$("#cm-groups")});