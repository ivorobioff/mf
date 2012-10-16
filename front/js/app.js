var Handlers={Abstract:{}};var Views={Abstract:{}};var Models={};var Lib={};
(function(){var initializing=false,fnTest=/xyz/.test(function(){xyz;})?/\b_super\b/:/.*/;this.Class=function(){};Class.extend=function(prop){var _super=this.prototype;initializing=true;var prototype=new this();initializing=false;for(var name in prop){prototype[name]=typeof prop[name]=="function"&&typeof _super[name]=="function"&&fnTest.test(prop[name])?(function(name,fn){return function(){var tmp=this._super;this._super=_super[name];var ret=fn.apply(this,arguments);this._super=tmp;return ret;};})(name,prop[name]):prop[name];}
function Class(){if(!initializing&&this.initialize)
this.initialize.apply(this,arguments);}
Class.prototype=prototype;Class.prototype.constructor=Class;Class.extend=arguments.callee;return Class;};})();
$(function(){Views.Abstract.ContextMenu=Backbone.View.extend({_real_menu:null,_is_shown:false,_coor:{},render:function(){this._real_menu=$(this.$el.html());$('body').append(this._real_menu);},show:function(coor){this._coor=coor;this._real_menu.show();this._setPosition();this._is_shown=true;},hide:function(){this._real_menu.hide();this._is_shown=false;},getDomMenu:function(){return this._real_menu;},isShown:function(){return this._is_shown;},_setPosition:function(){this._real_menu.css({left:this._coor.x,top:this._coor.y});}});Views.Abstract.ContextMenu._INSTANCE=null;Views.Abstract.ContextMenu.getInstance=function(){};});
$(function(){Views.CategoryCMenu=Views.Abstract.ContextMenu.extend({initialize:function(){this.render();}});Views.CategoryCMenu.getInstance=function(settings){if(Views.CategoryCMenu._INSTANCE==null){Views.CategoryCMenu._INSTANCE=new Views.CategoryCMenu(settings);}
return Views.CategoryCMenu._INSTANCE;}});
$(function(){Views.GroupCMenu=Views.Abstract.ContextMenu.extend({initialize:function(){this.render();}});Views.GroupCMenu.getInstance=function(settings){if(Views.GroupCMenu._INSTANCE==null){Views.GroupCMenu._INSTANCE=new Views.GroupCMenu(settings);}
return Views.GroupCMenu._INSTANCE;}});
$(function(){Views.PTable=Backbone.View.extend({_category_handler:null,_group_handler:null,events:{"click .cat-item":function(e){if(this._category_handler==null){this._category_handler=new Handlers.Category();}
this._category_handler.handleCMenu(e);},'click .group-item':function(e){if(this._group_handler==null){this._group_handler=new Handlers.Group();}
this._group_handler.handleCMenu(e);}}});});
$(function(){Handlers.Abstract.Category=Class.extend({context_menu:null,handleCMenu:function(e){this.context_menu.show({x:e.pageX,y:e.pageY});}});})
$(function(){Handlers.Category=Handlers.Abstract.Category.extend({context_menu:null,initialize:function(){this.context_menu=Views.CategoryCMenu.getInstance({el:$("#cm-cats")});}});})
$(function(){Handlers.Group=Handlers.Abstract.Category.extend({context_menu:null,initialize:function(){this.context_menu=Views.GroupCMenu.getInstance({el:$("#cm-groups")});}});});