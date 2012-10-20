
var Handlers={Abstract:{},Interface:{}};var Views={Abstract:{},Interface:{}};var Collections={Abstract:{},Interface:{}};var Models={Abstract:{},Interface:{}};var Lib={Abstract:{},Interface:{}};var Resources={};
Resources={groups:"/operations/planner/get-groups"};
String.prototype.toCamelCase=function(){var arr=this.split("-");var n_str='';for(var i in arr){n_str+=arr[i].charAt(0).toUpperCase()+arr[i].substr(1);}
return n_str.charAt(0).toLowerCase()+n_str.substr(1);}
Backbone.sync=function(method,model,options){var url=_.isFunction(model.url)?model.url():model.url;var method_map={"create":"POST","update":"POST","delete":"POST","read":"GET"};if(!_.has(method_map,method)){return false;}
var settings={type:method_map[method],url:url,data:model.toJSON(),dataType:"json",success:_.isFunction(options.success)?options.success:function(){},error:_.isFunction(options.error)?options.error:function(){},}
return $.ajax(settings);};Backbone.Model.prototype.is_ok=false;Backbone.Model.prototype.parse=function(r){Backbone.Model.prototype.is_ok=(r.status=="ok");return r.data;};Backbone.Collection.prototype.is_ok=false;Backbone.Collection.prototype.parse=function(r){Backbone.Collection.prototype.is_ok=(r.status=="ok");return r.data;};
(function(){var initializing=false,fnTest=/xyz/.test(function(){xyz;})?/\b_super\b/:/.*/;this.Class=function(){};Class.extend=function(prop){var _super=this.prototype;initializing=true;var prototype=new this();initializing=false;for(var name in prop){prototype[name]=typeof prop[name]=="function"&&typeof _super[name]=="function"&&fnTest.test(prop[name])?(function(name,fn){return function(){var tmp=this._super;this._super=_super[name];var ret=fn.apply(this,arguments);this._super=tmp;return ret;};})(name,prop[name]):prop[name];}
function Class(){if(!initializing&&this.initialize)
this.initialize.apply(this,arguments);}
Class.prototype=prototype;Class.prototype.constructor=Class;Class.extend=arguments.callee;return Class;};})();
Lib.Eventor=Class.extend(Backbone.Events);Lib.Eventor._INSTANCE=null;Lib.Eventor.getInstance=function(){if(Lib.Eventor._INSTANCE==null){Lib.Eventor._INSTANCE=new Lib.Eventor();}
return Lib.Eventor._INSTANCE;}
Models.Group=Backbone.Model.extend({});
Collections.Groups=Backbone.Collection.extend({model:Models.Group,url:Resources.groups});
$(function(){Views.Abstract.ContextMenu=Backbone.View.extend({_is_shown:false,_coor:{},_context_menu_handler:null,events:{"click a":function(e){if(this._context_menu_handler instanceof Handlers.Interface.ContextMenu){this._context_menu_handler.doAction(e);}
return false;}},initialize:function(){this.render();this.$el.mousedown(function(){return false;});Lib.Eventor.getInstance().on("click:body",function(){if(this.isShown()){this.hide();}},this);},render:function(){this.$el=$(this.$el.html());$('body').append(this.$el);},show:function(coor){this._coor=coor;this.$el.show();this._setPosition();this._is_shown=true;},hide:function(){this.$el.hide();this._is_shown=false;},getDomMenu:function(){return this.$el;},isShown:function(){return this._is_shown;},_setPosition:function(){this.$el.css({left:this._coor.x,top:this._coor.y});}});});
$(function(){Views.Abstract.Dialogs=Backbone.View.extend({_is_shown:false,_dialog_handler:null,_data:null,events:{"click .cancel-button, .dlg-close":function(){if(this._dialog_handler instanceof Handlers.Interface.DialogActions){this._dialog_handler.doCancel();}
return false;},"click .submit-button":function(){if(this._dialog_handler instanceof Handlers.Interface.DialogActions){this._dialog_handler.doSubmit();}}},initialize:function(){this.render();},render:function(){this.$el=$(this.$el.html());$("body").append(this.$el);},show:function(){this.$el.show();this._adjustWindow();this._is_shown=true;},hide:function(){this.$el.hide();this._is_shown=false;},isShown:function(){return this._is_shown;},_adjustWindow:function(){var $dlg=this.$el.find(".dlg");var top=Math.round($dlg.height()/2);$dlg.css("margin-top","-"+top+"px");},setData:function(data){this._data=data;return this;}});});
$(function(){Handlers.Interface.ContextMenu=Class.extend({show:function(e){},doAction:function(e){}});})
$(function(){Handlers.Interface.DialogActions=Class.extend({doCancel:function(){},doSubmit:function(){},});})
$(function(){Views.App=Backbone.View.extend({el:$("html"),events:{"mousedown body":function(){Lib.Eventor.getInstance().trigger("click:body")}}});Views.App._INSTANCE=null;Views.App.getInstance=function(){if(Views.App._INSTANCE==null){Views.App._INSTANCE=new Views.App();}
return Views.App._INSTANCE;}});
$(function(){Views.CategoryCMenu=Views.Abstract.ContextMenu.extend({el:$("#cm-cats"),initialize:function(){Views.Abstract.ContextMenu.prototype.initialize.apply(this,arguments);this._context_menu_handler=new Handlers.CategoryContextMenu();}});Views.CategoryCMenu._INSTANCE=null;Views.CategoryCMenu.getInstance=function(){if(Views.CategoryCMenu._INSTANCE==null){Views.CategoryCMenu._INSTANCE=new Views.CategoryCMenu();}
return Views.CategoryCMenu._INSTANCE;}});
$(function(){Views.GroupCMenu=Views.Abstract.ContextMenu.extend({el:$("#cm-groups"),initialize:function(){Views.Abstract.ContextMenu.prototype.initialize.apply(this,arguments);this._context_menu_handler=new Handlers.GroupContextMenu();}});Views.GroupCMenu._INSTANCE=null;Views.GroupCMenu.getInstance=function(){if(Views.GroupCMenu._INSTANCE==null){Views.GroupCMenu._INSTANCE=new Views.GroupCMenu();}
return Views.GroupCMenu._INSTANCE;}});
$(function(){Views.PTable=Backbone.View.extend({_category_handler:null,_group_handler:null,events:{"click .cat-item":function(e){if(this._category_handler==null){this._category_handler=new Handlers.CategoryContextMenu();}
this._category_handler.show(e);return false;},'click .group-item':function(e){if(this._group_handler==null){this._group_handler=new Handlers.GroupContextMenu();}
this._group_handler.show(e);return false;}}});});
$(function(){Views.NewCatDialog=Views.Abstract.Dialogs.extend({el:$("#new-cat-dlg"),initialize:function(){Views.Abstract.Dialogs.prototype.initialize.apply(this,arguments);this._dialog_handler=new Handlers.NewCatDialogHandler();}});Views.NewCatDialog._INSTANCE=null;Views.NewCatDialog.getInstance=function(){if(Views.NewCatDialog._INSTANCE==null){Views.NewCatDialog._INSTANCE=new Views.NewCatDialog();}
return Views.NewCatDialog._INSTANCE;}});
$(function(){Handlers.CategoryContextMenu=Handlers.Interface.ContextMenu.extend({show:function(e){Views.CategoryCMenu.getInstance().show({x:e.pageX,y:e.pageY});},doAction:function(e){alert("wow");},});})
$(function(){Handlers.GroupContextMenu=Handlers.Interface.ContextMenu.extend({_groups_collection:null,show:function(e){Views.GroupCMenu.getInstance().show({x:e.pageX,y:e.pageY});},doAction:function(e){var action=$(e.target).attr("action");if(!_.isString(action)){return;}
var method=action.toCamelCase();if(!_.isFunction(this[method])){return;}
this[method]();},addCategory:function(){if(_.isNull(this._groups_collection)){this._groups_collection=new Collections.Groups();}
this._groups_collection.fetch({success:function(collection){if(collection.is_ok){Views.NewCatDialog.getInstance().show();Views.GroupCMenu.getInstance().hide();}}});}});});
$(function(){Handlers.NewCatDialogHandler=Handlers.Interface.DialogActions.extend({doCancel:function(){Views.NewCatDialog.getInstance().hide();},doSubmit:function(){Views.NewCatDialog.getInstance().hide();}});});