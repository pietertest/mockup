$(function() {
	
$.fn.extend({
	tabcomplete: function(options) {

		return this.each(function() {
			$.Tabcompleter(this, options);
		});

	}
});

$.Tabcompleter = function(input, options) {
	$input = $(input);

	$input.bind("blur", function() {
		$.getJSON("http://localhost/?page=zoeken&action=save", {}, function(data) {
			var html = "";


			$.each(data, function(index, item) {
				html += item.message;
			});
			$("#result").html(html).show("slow");
		});
	});
}

});