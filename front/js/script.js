$(function(){
	var person = new Backbone.Model({
		name: "Bill",
		message: ''
	});

	person.on("change:name", function(){
		this.message = "it was " + this.previous("name") + " now it is " + this.get("name");
	});

	person.set({name: "Johm"});

	var NameView = Backbone.View.extend({
		initialize: function(){
			this.$el.html('<span id="greeting">Hello World!!!</span>');
		},
		events:{
			"click #greeting": "greet"
		},
		greet: function(){
			alert("Hello world");
		}
	});

	var name_view = new NameView({el: $("#name")});
});