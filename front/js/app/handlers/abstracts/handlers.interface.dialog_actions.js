/**
 * Интерфейс для объектов обрабатывающие стандартные кнопки в диалогах
 */
$(function(){
	Handlers.Interface.DialogActions = Class.extend({
		doCancel: function(dialog){},
		doSubmit: function(dialog){},
	});
})