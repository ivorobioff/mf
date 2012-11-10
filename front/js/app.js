var Helpers = {Abstract: {}, Interface: {}};
var Views = {Abstract: {}, Interface: {}};
var Collections = {Abstract: {}, Interface: {}};
var Models = {Abstract: {}, Interface: {}};
var Lib = {Abstract: {}, Interface: {}};
var Resources = {};
var DataSource = {};
/**
 * Местоположение ресурсов на сервере
 */
Resources = {
	group: '/operations/planner/{method}-group',
	category: '/operations/planner/{method}-category',
	pseudo_category_withdrawal: '/operations/flow/withdrawal',
	request_amount: '/operations/flow/request-amount',
	return_amount: '/operations/flow/return-amount',
	budget_withdrawal: '/operations/budget/withdrawal',
	budget_deposit: '/operations/budget/deposit'
};
//Добавил метод в объект String
String.prototype.toCamelCase = function(){
	
	var arr = this.split('-');
	var n_str = '';
	
	for (var i in arr){
		n_str += arr[i].charAt(0).toUpperCase() + arr[i].substr(1); 
	}
	
	return n_str.charAt(0).toLowerCase() + n_str.substr(1);
}

/**
 * Полезная функция для дебага. Выводит хэш атрибутов объекта
 */
function pred(data){
	alert(JSON.stringify(data));
}


//Переопределил Backbone.sync
Backbone.sync = function(method, model, options) {};

/**
 * Соберает данны с формы для сабмита
 */
$.fn.dataForSubmit = function(){
	var data = {};
	this.find('[data-submit]').each(function(e){
		var $this = $(this);
		var $val = $this.val();
		
		if (($this.attr('type') == 'checkbox' || $this.attr('type') == 'radio') && !$this.attr('checked')){
			$val = 0;
		}

		data[$this.attr('name')] = $val;
	});
	
	return data;
};

/**
 * обновляет данные в елементах которые имеют атрибут data-field 
 */
$.fn.refreshDataFields = function(data){

	for (var i in data){
		this.find('[data-field="' + i + '"]').html(_.escape(data[i]));
	}
	
	return data;
};

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
      if ( !initializing && this.initialize )
        this.initialize.apply(this, arguments);
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
Lib.Eventor = Class.extend(Backbone.Events);
Lib.Eventor._INSTANCE = null;

Lib.Eventor.getInstance = function(){
	
	if (Lib.Eventor._INSTANCE == null){
		Lib.Eventor._INSTANCE = new Lib.Eventor();
	}
	
	return Lib.Eventor._INSTANCE;
};
Lib.Collection = Class.extend({
	_data: null,
	
	initialize: function(){
		this._data = {};
	},
	
	add: function(key, value){
		var data = {};
		data[key] = value;
		$.extend(this._data, data);
		return this;
	},
	
	get: function(key){
		return this._data[key];
	}
});
/**
 * Класс для обработки ошибок полученных с сервера
 */
Lib.ErrorHandler = Class.extend({
	
	/**
	 * @private
	 */
	_error_data: null,
	
	initialize: function (data){
		this._error_data = data;
	},
	
	/**
	 * Показывает ошибки
	 * @public
	 */
	display: function(){
		var message = '';
		var n = '';
		for (var i in this._error_data){
			message +=n + this._error_data[i];
			n = "\n";
		}
		
		alert(message);
	},
	
	getData: function(){
		return this._error_data;
	}
	
});
/**
 * Паттерн регистратор
 */
Lib.Register = {
	
	_data: {},
	
	add: function(key, value){
		var data = {};
		data[key] = value;
		$.extend(this._data, data);
	},
	
	get: function(key){
		return this._data[key];
	}
};
/**
 * RemoteRun позволяет с любого места запускать код от имени другого объекта.
 */
Lib.RemoteRun = {
		
	_channels: new Lib.Collection(),
	
	/**
	 * Подключается к объекту через заданный канал и запускает код аннонимной функции
	 * от имении подключенного объекта.
	 * @param String channel - токен канала
	 * @param Function func анонимная функция которая будет запущена от имени подключенного объекта
	 * @param Array params - дополнительные параметры для функции
	 */
	execute: function(channel, func, params){
		var context = this._channels.get(channel);
		
		if (_.isFunction(func)){
			func.apply(context, _.isUndefined(params) ? [] : params);
		}
	},
	
	/**
	 * Создает канал для подключения к объкту
	 * @param String channel - токен канала
	 * @param Object - ссылка на подключаемый объект. 
	 */
	access: function(channel, context){
		this._channels.add(channel, context);
	}
};
/**
 * Либа для отправки независимых от моделей ajax запросов и обновления моделей после успешного ответа.
 */
Lib.Requesty = {
	
	_request_types: {
		'create': 'post',
		'update': 'post',
		'read': 'get',
		'delete': 'post',
		'post': 'post',
		'get': 'get'
	},
	
	_method: null,
	
	_options_clean: {
		'data': {},
		'id': null,
		'success': function(){},
		'error': function(){},
		'followers': {
			'update_models': {},
			'delete_models': []
		},
		'url': ''
	},
	
	_options: null,
	
	_followers_structure: null,
	
	create: function(options){
		this._method = 'create';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	update: function(options){
		this._method = 'update';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	read: function(options){
		this._method = 'read';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	remove: function(options){
		this._method = 'delete';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	post: function(options){
		this._method = 'post';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	get: function(options){
		this._method = 'get';
		this._assignOptions(options);
		
		this._makeRequest();
	},
	
	_makeRequest: function(){
		this._prepare()._send();
	},
	
	_assignOptions: function(options){
		this._options = _.extend(_.clone(this._options_clean), options);
	},
	
	_prepare: function(){
			
		this._followers_structure = this._options.followers;
		
		if (this._options.data instanceof Models.Abstract.Model){
			this._options.data = this._options.data.toJSON();
		}
		
		if (!_.isNull(this._options.id) && !_.isUndefined(this._options.id)){
			this._options.data.id = this._options.id;
		}
		
		this._options.url = this._options.url.replace('{method}', this._method); 
		
		if(!_.has(this._options.followers, 'update_models')
				&& !_.has(this._options.followers, 'delete_models')){
			
			if (this._method == 'delete'){
				this._options.followers = {delete_models: this._options.followers}
			} else {
				this._options.followers = {update_models: this._options.followers}
			}
		}
		
		if (this._options.followers.update_models instanceof Models.Abstract.Model){
			this._options.followers.update_models = {'def': this._options.followers.update_models};
		}
		
		if (this._options.followers.delete_models instanceof Models.Abstract.Model){
			this._options.followers.delete_models = [this._options.followers.delete_models];
		}
		
		if (_.isUndefined(this._options.followers.update_models)){
			this._options.followers.update_models = {};
		}
		
		if (_.isUndefined(this._options.followers.delete_models)){
			this._options.followers.delete_models = [];
		}
		
		return this;
	},
	
	_send: function(){
		
		$.ajax({
			type: this._request_types[this._method],
			url: this._options.url,
			data: this._options.data,
			dataType: 'json',
			success: $.proxy(function(data){
					
				var updates = this._options.followers.update_models;
				var deletes = this._options.followers.delete_models;
				
				if (_.isObject(data)){
					for (var i in data){
						if (updates[i] instanceof Models.Abstract.Model){
							updates[i].set(data[i]);
						}
					}
				}
				
				for (var i in deletes){
					if (deletes[i] instanceof Models.Abstract.Model){
						deletes[i].destroy();
					}
				}
				
				if (_.isFunction(this._options.success)){
					this._options.success(this._followers_structure, data);
				}
			}, this),
			
			error: $.proxy(function(data){
				if (data.status == '403'){
					
					var jdata;
					
					try{
						jdata = $.parseJSON(data.responseText);					
					} catch(e) {
						throw 'Response error: ' + data.responseText;
					}
					
					if( _.isFunction(this._options.error)){
						this._options.error(new Lib.ErrorHandler(jdata), this._options.followers);
						return ;
					}
				}
				
				throw 'Response error: ' + data.responseText;
			}, this)
		});
	}
	
}
Collections.Abstract.Collection = Backbone.Collection.extend({
	
	/**
	 * ищет модель в коллекции по заданному условию и возвращает реальные объекты модели
	 * @public
	 */
	search: function(data){
		
		var res = this.where(data);
		var _return = [];
		
		if (res.length == 0){
			return _return;
		}
		
		for (var i in res){
			_return.push(this.get(res[i].id));
		}
		
		return _return;
	},
	
	/**
	 * ищет модель в коллекции по заданному условию и возвращает реальный объект модели
	 * @public
	 */
	searchOne: function(data){
		var res = this.search(data);
		
		if (res.length == 0 ){
			return res;
		}
		
		return res.shift();
	},
	
	clear: function(){
		this.forEach(function(model){
			model.destroy();
		});
	}
	
});
Models.Abstract.Model = Backbone.Model.extend({
});
Models.Group = Models.Abstract.Model.extend({
	urlRoot: Resources.group
});
Models.Category = Models.Abstract.Model.extend({
	urlRoot: Resources.category
});
Models.Budget = Models.Abstract.Model.extend({
	
});

Models.Budget._INSTANCE == null;

Models.Budget.getInstance = function(){
	if (Models.Budget._INSTANCE == null){
		Models.Budget._INSTANCE = new Models.Budget(DataSource.budget);
	}
	
	return Models.Budget._INSTANCE;
}
Models.Log = Models.Abstract.Model.extend({
	
});
Collections.Groups = Collections.Abstract.Collection.extend({
	model: Models.Group
});

Collections.Groups._INSTANCE = null;

Collections.Groups.getInstance = function(){	
	
	if (Collections.Groups._INSTANCE == null){
		Collections.Groups._INSTANCE = new Collections.Groups(DataSource.Groups);
	}
	
	return Collections.Groups._INSTANCE;
}
Collections.Categories = Collections.Abstract.Collection.extend({
	model: Models.Category
});

Collections.Categories._INSTANCE = null;

Collections.Categories.getInstance = function(){
	
	if (Collections.Categories._INSTANCE == null){
		Collections.Categories._INSTANCE = new Collections.Categories(DataSource.Categories);
	}
	
	return Collections.Categories._INSTANCE;
}
Collections.Logs = Collections.Abstract.Collection.extend({
	model: Models.Log
});

Collections.Logs._INSTANCE = null;

Collections.Logs.getInstance = function(){
	
	if (Collections.Logs._INSTANCE == null){
		Collections.Logs._INSTANCE = new Collections.Logs(DataSource.Logs);
	}
	
	return Collections.Logs._INSTANCE;
}
Views.Abstract.View = Backbone.View.extend({
	
	_models: null,
	_params: null,
	
	initialize: function(){
		this._models = new Lib.Collection();
		this._params = new Lib.Collection();
	},
	
	addModel: function(key, model){
		this._models.add(key, model);
		return this;
	},
	
	getModel: function(key){
		return this._models.get(key);
	},
	
	getDom: function(){
		return this.$el;
	},
	
	/**
	 * Позволяет прикрепить вьюшке дополнительные параметры
	 */
	assign: function (key, value){
		
		this._params.add(key, value);
		
		return this;
	},
	
	/**
	 * получает прикрепленный параметр
	 */
	getParam: function(key){
		return this._params.get(key);
	}
});
$(function(){
	Views.Abstract.Refreshable = Views.Abstract.View.extend({
		initialize: function(){
			
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});
/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Abstract.Group = Views.Abstract.View.extend({
		
		_template: $('#group-table'),
			
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var template = Handlebars.compile(this._template.html());
			this.$el = $(template(this.model.toJSON()));
			this.$el.insertBefore('#groups-hook');
			
			this.delegateEvents();
			return this;
		}
	});
});
$(function(){
	Views.Abstract.Category = Views.Abstract.Refreshable.extend({
		
		_template: $('#category-row'),
		
		events: {
			'click .cat-item': function(e){
				Views.CategoryContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			}
		},
		
		render: function(parent){
			var template = Handlebars.compile(this._template.html());
			
			this.setElement($(template(this.model.toJSON())));
			
			this.$el.insertBefore(parent.$el.find('#categories-hook'));
	
			return this;
		}
	});
});
/**
 * Вью конетекстного меню
 */
$(function(){
	Views.Abstract.ContextMenu = Views.Abstract.View.extend({
		
		_is_shown: false,
		
		_coor: {},
		
		_context_menu_helper: null,
		
		_context: null,
		
		events:{
			'click a': function(e){
				
				this._context_menu_helper.doAction(e);
				this.hide();
				return false;
			}
		},
		
		initialize: function(){	
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.render();
			
			this.$el.mousedown(function(){
				return false;
			});
			
			Lib.Eventor.getInstance().on('click:body', function(){
				if (this.isShown()){
					this.hide();
				}
			}, this);
		},
		
		render: function(){
			this.$el = $(this._template.html());
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
		
		setContext: function(context){
			this._context = context;
			return this;
		},

		getContext: function(){
			return this._context;
		},
		
		_setPosition: function(){
			this.$el.css({left: this._coor.x, top: this._coor.y});
		}
	});
});
$(function(){
	Views.Abstract.Dialogs = Views.Abstract.View.extend({
		
		_template: null,
		
		_is_shown: false,
		
		/**
		 * Хелпер диалога. Обрабатывает кнопки submit и cancel
		 * @protected
		 */
		_dialog_helper: null,
		
		/**
		 * HTML шаблон диалогов
		 */
		_layout: $("#dialog-layout"),
		
		/**
		 * Общие данные layout-а для всех диалогов. 
		 * Могут быть переопределены в подклассах
		 * @private
		 */
		_layout_data: {
			'title': 'Диалоговое окно',
			'submit': 'Применить',
			'cancel': 'Отмена'
		},
		
		events: {
			'click .cancel-button, .dlg-close': function(){
				
				this._dialog_helper.doCancel();
				
				return false;
			},
			'click .submit-button': function(){
				
				this._dialog_helper.doSubmit();
			}
		},
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var content_template = Handlebars.compile(this._template.html());
			var layout_template = Handlebars.compile(this._layout.html());
	
			var layout_data = {};
			
			$.extend(layout_data, this._layout_data, this._getLayoutData());
			
			this.$el = $(layout_template(layout_data));
			
			this.$el.find('#dialog-content').html(content_template(this._getContentData()));			
			
			$('body').append(this.$el);
		},
		
		/**
		 * Возвращает объект с данными для вывода в шаблоне контента.
		 * По умолчанию возвращает пустой объек. 
		 * Может быть переопределена в подклассах.
		 * 
		 * @protected
		 * @return Object
		 */
		_getContentData: function(){
			return {};
		},
		
		/**
		 * Возвращает объект с данными для вывода в шаблоне layout-а.
		 * @protected
		 */
		_getLayoutData: function(){
			return {}
		},
		
		/**
		 * Запускается сразу перед показом окна.
		 * @protected
		 */
		_update: function(){
			
		},
		
		show: function(){
			this._update();
			this.$el.show();
			this._adjustWindow();
			this._is_shown = true;
		},
		
		hide: function(){
			this.$el.hide();
			this._is_shown = false;
		},
		
		isShown: function(){
			return this._is_shown;
		},
		
		/**
		 * Служит для блокировки всего или отдельных элемнтов окна во время ajax запроса.
		 * Можно переопределить в подклассах. По умолчанию, данная функция блокирует кнопку "Применит"
		 * @public
		 */
		disableUI: function(){
			this.$el.find('.submit-button').attr('disabled', 'disabled');
			return this;
		},
		
		enableUI: function(){
			this.$el.find('.submit-button').removeAttr('disabled');
			return this;
		},
		
		/**
		 * @private
		 */
		_adjustWindow: function(){
			
			var $dlg = this.$el.find('.dlg');
			
			var top = Math.round($dlg.height() / 2);
			
			$dlg.css('margin-top', '-'+top+'px');
		},
		
		_resetFields: function(){
			this.$el.find('input[type=text], textarea').val('');
			this.$el.find('select').val(0);
			this.$el.find('input[type=checkbox], input[type=radio]').removeAttr('checked');
		}
	});
});
$(function(){
	Views.App = Views.Abstract.View.extend({
		
		el: 'html',
		
		events: {
			'mousedown body': function(){
				Lib.Eventor.getInstance().trigger('click:body')
			}
		}
	});
	
	Views.App._INSTANCE = null;
	Views.App.getInstance = function(){
		
		if (Views.App._INSTANCE == null){
			Views.App._INSTANCE = new Views.App();
		}
		
		return Views.App._INSTANCE;
	}
});

$(function(){
	Views.Budget = Views.Abstract.Refreshable.extend({
				
		_budget_menu: null,
		
		events: {
			'click #menu a': function(e){
				
				if (this._budget_menu == null){
					this._budget_menu = new Helpers.BudgetMenu(this);
				}
				
				this._budget_menu.doAction(e);
				
				return false;
			}
		},
		
		initialize: function(){
			Views.Abstract.Refreshable.prototype.initialize.apply(this, arguments);
			this.render();
		},
		
		render: function(){
			var template =  Handlebars.compile(this.$el.html());
			this.$el.html(template(this.model.toJSON()));
		}
	});
});
$(function(){
	Views.CategoryContextMenu = Views.Abstract.ContextMenu.extend({
		_template: $('#cm-cats'),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_helper = new Helpers.CategoryContextMenu(this);
		}
	});	
	
	Views.CategoryContextMenu._INSTANCE = null;
	
	Views.CategoryContextMenu.getInstance = function(){
		
		if (Views.CategoryContextMenu._INSTANCE == null){
			Views.CategoryContextMenu._INSTANCE = new Views.CategoryContextMenu();
		}
		
		return Views.CategoryContextMenu._INSTANCE;
	}
});
$(function(){
	Views.GroupContextMenu = Views.Abstract.ContextMenu.extend({
	
		_template: $('#cm-groups'),
		
		initialize: function(){
			Views.Abstract.ContextMenu.prototype.initialize.apply(this, arguments);
			this._context_menu_helper = new Helpers.GroupContextMenu(this);
		}
	
	});
	
	Views.GroupContextMenu._INSTANCE = null;
	
	Views.GroupContextMenu.getInstance = function(){
		
		if (Views.GroupContextMenu._INSTANCE == null){
			Views.GroupContextMenu._INSTANCE = new Views.GroupContextMenu();
		}
		
		return Views.GroupContextMenu._INSTANCE;
	}
});
/**
 * Вью таблицы в планнере
 */
$(function(){
	Views.Group = Views.Abstract.Group.extend({
		
		events: {
			'click .group-item': function(e){
				Views.GroupContextMenu.getInstance().setContext(this).show({x: e.pageX, y: e.pageY});
				return false;
			} 
		},
			
		initialize: function(){
			Views.Abstract.Group.prototype.initialize.apply(this, arguments);
			
			Collections.Categories.getInstance().on('add', $.proxy(function(model){
				if (model.get('group_id') == this.model.id){
					new Views.Category({model: model}).render(this);
				}
			}, this));
			
			Lib.Register.get('group_views').add('group_' + this.model.id, this);
			
			this.model.on('change', $.proxy(function(){
				this.refresh();
			}, this));
			
			this.model.on('destroy', $.proxy(function(){
				this.remove();
			}, this));
		},
		
		refresh: function(){
			this.$el.refreshDataFields(this.model.toJSON());
			return this;
		}
	});
});
$(function(){
	Views.Category = Views.Abstract.Category.extend({
		
		initialize: function(){
			Views.Abstract.Category.prototype.initialize.apply(this, arguments);
			
			this.model.on('change:group_id', $.proxy(function(model){
				this._reRender(Lib.Register.get('group_views').get('group_' + model.get('group_id')));
			}, this));
			
			this.model.on('destroy', $.proxy(function(){
				this.remove();
			}, this));
		},
		
		_reRender: function(parent){
			this.remove();
			this.render(parent);
		}
	});
});
$(function(){
	Views.NewCategoryDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#category-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.NewCategoryDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Создать новую категорию'};
		},
		
		/**
		 * Запускается перед тем как окно появляется. 
		 * Служит для обновления данных окна перед появлением самого окна.
		 * @protected
		 */
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			
			var options = '';
			var groups = Collections.Groups.getInstance().toJSON();
			
			for (var i in groups){
				options +='<option value=\'' + groups[i].id + '\'>' + groups[i].name + '</option>';
			}
			
			this.$el.find('#groups-select').html(options);
			
			this.$el.find('[name=title], [name=amount]').val('');
			this.$el.find('[name=pin]').removeAttr('checked');
						
			this.$el.find('[name=group_id]').val(this.getModel('group').id);
		}
	});
	
	Views.NewCategoryDialog._INSTANCE = null;
	
	Views.NewCategoryDialog.getInstance = function(){
		
		if (Views.NewCategoryDialog._INSTANCE == null){
			Views.NewCategoryDialog._INSTANCE = new Views.NewCategoryDialog();
		}
		
		return Views.NewCategoryDialog._INSTANCE;
	}
});
$(function(){
	Views.EditCategoryDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#category-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.EditCategoryDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Редактировать категорию'};
		},
		
		_update: function(){
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			
			var options = '';
			var groups = Collections.Groups.getInstance().toJSON();
			
			for (var i in groups){
				options +='<option value=\'' + groups[i].id + '\'>' + groups[i].name + '</option>';
			}
	
			this.$el.find('#groups-select').html(options);
			
			this.$el.find('[name=title]').val(this.getModel('category').get('title'));
			this.$el.find('[name=amount]').val(this.getModel('category').get('amount'));
			
			this.$el.find('[name=pin]').removeAttr('checked');
			
			if (this.getModel('category').get('pin') > 0){
				this.$el.find('[name=pin]').attr('checked', 'checked');
			} 
			
			this.$el.find('[name=group_id]').val(this.getModel('category').get('group_id'));
		}
	});
	
	Views.EditCategoryDialog._INSTANCE = null;
	
	Views.EditCategoryDialog.getInstance = function(){
		
		if (Views.EditCategoryDialog._INSTANCE == null){
			Views.EditCategoryDialog._INSTANCE = new Views.EditCategoryDialog();
		}
		
		return Views.EditCategoryDialog._INSTANCE;
	}
});
$(function(){
	Views.Confirmation = Views.Abstract.Dialogs.extend({
		
		_text: '',
		
		_template: $('#confirmation-dialog'),
	
		initialize: function (text, helper){
			this._text = text;
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);			
			this._dialog_helper = new helper(this);
		},
		
		_getLayoutData: function(){
			return {
				title:'Внимание!',
				submit: 'Да',
				cancel: 'Нет'
			};
		},
		
		_getContentData: function(){
			return {text: this._text};
		}
	});
});
$(function(){
	Views.NewGroupLink = Views.Abstract.View.extend({
		
		el: $('#new-gr'),
	
		events: {
			'click a': function(){
				Views.NewGroupDialog.getInstance().show();
				return false;
			}
		}
	});
});
$(function(){
	Views.NewGroupDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#group-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.NewGroupDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Создать новую группу'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this.$el.find('[name=name]').val('');
		}
	});
	
	Views.NewGroupDialog._INSTANCE = null;
	
	Views.NewGroupDialog.getInstance = function(){
		
		if (Views.NewGroupDialog._INSTANCE == null){
			Views.NewGroupDialog._INSTANCE = new Views.NewGroupDialog();
		}
		
		return Views.NewGroupDialog._INSTANCE;
	}
});
$(function(){
	Views.EditGroupDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#group-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.EditGroupDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Редактировать группу'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this.$el.find('[name=name]').val(_.escape(this.getModel('group').get('name')));
		}
	});
	
	Views.EditGroupDialog._INSTANCE = null;
	
	Views.EditGroupDialog.getInstance = function(){
		
		if (Views.EditGroupDialog._INSTANCE == null){
			Views.EditGroupDialog._INSTANCE = new Views.EditGroupDialog();
		}
		
		return Views.EditGroupDialog._INSTANCE;
	}
});
$(function(){
	Views.PseudoGroup = Views.Abstract.Group.extend({
		
	});
});
$(function(){
	Views.PseudoCategory = Views.Abstract.Category.extend({
		
	});
});
$(function(){
	Views.WithdrawalDialog = Views.Abstract.Dialogs.extend({
		
		_template: $('#withdrawal-dialog'),

		initialize: function(){
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);
			this._dialog_helper = new Helpers.WithdrawalDialog(this);
		}, 
		
		_getLayoutData: function(){
			return {title:'Cнять сумму'};
		},
	
		_update: function(){			
			Views.Abstract.Dialogs.prototype._update.apply(this, arguments);
			this._resetFields();
		}
	});
	
	Views.WithdrawalDialog._INSTANCE = null;
	
	Views.WithdrawalDialog.getInstance = function(){
		
		if (Views.WithdrawalDialog._INSTANCE == null){
			Views.WithdrawalDialog._INSTANCE = new Views.WithdrawalDialog();
		}
		
		return Views.WithdrawalDialog._INSTANCE;
	}
});
$(function(){
	Views.Prompt = Views.Abstract.Dialogs.extend({
		
		_options: null,
		
		_template: $('#prompt-dialog'),
			
		initialize: function (options, helper){
			this._options = options;
			Views.Abstract.Dialogs.prototype.initialize.apply(this, arguments);			
			this._dialog_helper = new helper(this);
		},
		
		_getLayoutData: function(){
			return {
				title:this._options.title,
				submit: 'Применить',
				cancel: 'Отмена'
			};
		},
		
		_getContentData: function(){
			return {label: this._options.label};
		},
		
		getValue: function(){
			return this.$el.find('[name=value]').val();
		},
		
		_update: function(){
			this.$el.find('[name=value]').val('');
		}
	});
});
$(function(){
	Views.Log = Views.Abstract.View.extend({
		
		_template: $('#log-row'),
		
		initialize: function(){
			Views.Abstract.View.prototype.initialize.apply(this, arguments);
			
			this.render();
		},
		
		render: function(){
			var tmp = Handlebars.compile(this._template.html());
			this.setElement($(tmp(this.model.toJSON())));
			
			this.$el.insertAfter($('#log-hook'));
		}
	});
});
$(function(){
	Views.Search = Views.Abstract.View.extend({
		el: $('#search-bl'),
		
		events: {
			'click input': function(){
				alert('asdf');
			}
		}
		
	});
});
Helpers.Abstract.Helper = Class.extend({
	_view: null,
	
	initialize: function(view){
		this._view = view;
	}
});
/**
 * Абстрактный хэлпер для контекстного меню
 */
Helpers.Abstract.Menu = Helpers.Abstract.Helper.extend({

	/**
	 * @public
	 */
	doAction: function(e){
		var action =  $(e.target).attr('action');
		
		if (!_.isString(action)){
			return ;
		}
		
		var method = action.toCamelCase();
		
		if (!_.isFunction(this[method])){
			return ;
		}
		
		this[method]();
	}
});
/**
 * Класс для обработки контекстого меню категорий
 */
$(function(){
	Helpers.CategoryContextMenu = Helpers.Abstract.Menu.extend({	
		
		_delete_confirm: null,
		_return_confirm: null,
		
		editCategory: function(){
			Views.EditCategoryDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		deleteCategory: function(){
						
			if (_.isNull(this._delete_confirm)){				
				var text = 'Вы уверены что хотите удалить данную категорию?';
				this._delete_confirm = new Views.Confirmation(text, Helpers.DeleteCategoryConfirmation);
			}
			
			this._delete_confirm.addModel('category', this._view.getContext().model).show();
		},
		
		withdrawal: function(){
			Views.WithdrawalDialog.getInstance().addModel('category', this._view.getContext().model).show();
		},
		
		returnAmount: function(){
			
			if (_.isNull(this._return_confirm)){
				var text = 'Вы уверены, что хотите вернуть оставшуюся сумму?';
				this._return_confirm = new Views.Confirmation(text, Helpers.ReturnAmountConfirmation);
			}
			
			this._return_confirm.addModel('category', this._view.getContext().model).show();
		}
	});
})
/**
 * Класс для обработки контекстного меню групп
 */
$(function(){
	Helpers.GroupContextMenu = Helpers.Abstract.Menu.extend({
		
		_delete_confirmation: null,
		
		addCategory: function(){
			Views.NewCategoryDialog.getInstance().addModel('group', this._view.getContext().model).show();
		},
		
		editGroup: function(){
			Views.EditGroupDialog.getInstance().addModel('group', this._view.getContext().model).show();
		},
		
		deleteGroup: function(){
			
			if (_.isNull(this._delete_confirmation)){
				var text = 'Вы уверены, что хотите удалить данную группу?';
				this._delete_confirmation = new Views.Confirmation(text, Helpers.DeleteGroupConfirmation);
			}
			
			this._delete_confirmation.addModel('group', this._view.getContext().model).show();
		}
	});
});
/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.NewCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			
			this._view.disableUI();
			
			Lib.Requesty.create({
				
				data: this._view.getDom().dataForSubmit(),
				
				url: Resources.category,
				
				success: $.proxy(function(followers){
					Collections.Categories.getInstance().add(followers.def);
					this._view.enableUI();
					this._view.hide();
				}, this),
				
				error: $.proxy(function(errors){
					this._view.enableUI();
					errors.display();
				}, this),
				
				followers: {
					def: new Models.Category(),
					budget: Models.Budget.getInstance()
				}
			});
		}
	});
});
/**
 * Класс обрабатывающий стандартные кнопи в диалоги создания новой котегории
 */
$(function(){
	Helpers.EditCategoryDialog = Helpers.Abstract.Helper.extend({
		
		doCancel: function(){
			this._view.hide();
		},
		
		doSubmit: function(){
			
			this._view.disableUI();
			
			Lib.Requesty.update({
				
				url: Resources.category,
				
				data: this._view.getDom().dataForSubmit(),
				
				id: this._view.getModel('category').id,
				
				success: $.proxy(function(){
					this._view.enableUI();
					this._view.hide();
				}, this),
				
				error: $.proxy(function(error_handler){
					this._view.enableUI();
					error_handler.display();
				}, this),
				
				followers: {
					def: this._view.getModel('category'),
					budget: Models.Budget.getInstance()
				}
			});
		}
	});
});
Helpers.DeleteCategoryConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		
		Lib.Requesty.remove({
			
			url: Resources.category,
			
			data: {id: this._view.getModel('category').id},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				this._view.hide();
				error_handler.display();
			}, this),
			
			followers: {
				delete_models: this._view.getModel('category'),
				update_models: Models.Budget.getInstance()
			}
		});
	}
});
Helpers.NewGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		Lib.Requesty.create({
			
			data: this._view.getDom().dataForSubmit(),
			
			url: Resources.group,
			
			success: $.proxy(function(model){
				Collections.Groups.getInstance().add(model);
				new Views.Group({model: model});
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				this._view.enableUI();
				errors.display();
			}, this),
			
			followers: new Models.Group()
		});
	}
});
Helpers.EditGroupDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){		
		this._view.disableUI();
		
		Lib.Requesty.update({
			
			url: Resources.group,
			
			data: this._view.getDom().dataForSubmit(),
			
			id: this._view.getModel('group').id,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				error_handler.display();
			}, this),
			
			followers: this._view.getModel('group')
		});
	}
});
Helpers.DeleteGroupConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		
		
		Lib.Requesty.remove({
			
			url: Resources.group,
			
			data: {id: this._view.getModel('group').id},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				this._view.enableUI();
				this._view.hide();
				error_handler.display();
			}, this),
			
			followers: this._view.getModel('group')
		});
	}
});
Helpers.WithdrawalDialog = Helpers.Abstract.Helper.extend({
	
	_request_dialog: null,
	
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		var data = this._view.getDom().dataForSubmit();		
		var comment = data.comment;
		
		this._view.disableUI();
			
		Lib.Requesty.post({
			url: Resources.pseudo_category_withdrawal,
			
			data: data,
			
			id: this._view.getModel('category').id,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				if (_.isUndefined(error_handler.getData().requested_amount)){
					error_handler.display();
					this._view.enableUI();
					return ;
				}
				
				var requested_amount = error_handler.getData().requested_amount;
				
				if (_.isNull(this._request_dialog)){
					
					var text = 'Сумма не может быть снята, поскольку это превысит ваш план. '+
						'Увеличить запланированую сумму для данной категории?';
					
					this._request_dialog = new Views.Confirmation(text, Helpers.AmountRequestDialog);
				}
				
				this._view.enableUI();
				this._view.hide();
				
				this._request_dialog
					.addModel('category', this._view.getModel('category'))
					.assign('requested_amount', requested_amount)
					.assign('comment', comment)
					.show();
				
			}, this),
			
			followers: {
				def: this._view.getModel('category'),
				budget: Models.Budget.getInstance()
			}
		});
	}
});
Helpers.AmountRequestDialog = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		var data = {
			requested_amount: this._view.getParam('requested_amount'),
			comment: this._view.getParam('comment')
		}
	
		Lib.Requesty.post({
			
			data: data,
			
			id: this._view.getModel('category').id,
			
			url: Resources.request_amount,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(error_handler){
				error_handler.display();
				this._view.enableUI();
			}, this),
			
			followers: {
				'def': this._view.getModel('category'),
				'budget': Models.Budget.getInstance()
			}
			
		});
	}
	
});
Helpers.ReturnAmountConfirmation = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		
		this._view.disableUI();
		
		Lib.Requesty.post({
			url: Resources.return_amount,
			id: this._view.getModel('category').id,
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				this._view.enableUI();
				this._view.hide();
				errors.display();
			}, this),
			
			followers: {
				'def': this._view.getModel('category'),
				'budget': Models.Budget.getInstance()
			}
		});
	}
});
Helpers.BudgetWithdrawalPrompt = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		Lib.Requesty.post({
			url: Resources.budget_withdrawal,
			data: {amount: this._view.getValue()},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				errors.display();
				this._view.enableUI();
			}, this),
			
			followers: this._view.getModel('budget')
		});
	}
});
Helpers.BudgetDepositPrompt = Helpers.Abstract.Helper.extend({
	doCancel: function(){
		this._view.hide();
	},
	
	doSubmit: function(){
		this._view.disableUI();
		Lib.Requesty.post({
			url: Resources.budget_deposit,
			data: {amount: this._view.getValue()},
			
			success: $.proxy(function(){
				this._view.enableUI();
				this._view.hide();
			}, this),
			
			error: $.proxy(function(errors){
				errors.display();
				this._view.enableUI();
			}, this),
			
			followers: this._view.getModel('budget')
		});
	}
});
Helpers.BudgetMenu = Helpers.Abstract.Menu.extend({
	
	_withdrawal_prompt: null,
	_deposit_prompt: null,
	
	withdrawal: function(){
		if (this._withdrawal_prompt == null){
			this._withdrawal_prompt = new Views.Prompt({
				title: 'Снять сумму',
				label: 'Сумма'
			}, Helpers.BudgetWithdrawalPrompt);
		}
		
		this._withdrawal_prompt.addModel('budget', this._view.model).show();
	},
	
	deposit: function(){
		if (this._deposit_prompt == null){
			this._deposit_prompt = new Views.Prompt({
				title: 'Внести сумму',
				label: 'Сумма'
			}, Helpers.BudgetDepositPrompt);
		}
		
		this._deposit_prompt.addModel('budget', this._view.model).show();
	}
});
