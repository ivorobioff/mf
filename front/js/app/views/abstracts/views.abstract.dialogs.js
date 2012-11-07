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