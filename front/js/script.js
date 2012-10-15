/**
 * Спецификация обозначений:
 * 1. Все названия классов пишутся в стиле CamelCase;
 * 2. Все переменные пишутся в стиле underscore;
 * 3. Все абстрактные классы начинаются с Abs.
 */
$(function(){	
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
	
	//-----------------------------------------------------
	
	var category_handler = new CategoryHandler();
	var group_handler = new GroupHandler();
	
	$('.count_tb').each(function(){
		new PTableView({el: this});
	});
	
});
