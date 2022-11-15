$(document).ready(function() {
	var dateFormat = "yy-mm-dd",
	from = $("#start_date").datepicker({
		dateFormat: dateFormat,
		minDate: -0,
	}).on("change", function() {
		to.datepicker("option", "minDate", getDate(this));
	}),
	to = $("#end_date").datepicker({
		dateFormat: dateFormat,
	}).on("change", function() {
		from.datepicker("option", "maxDate", getDate(this));
	});

	function getDate(element) {
		var date;
		try {
			date = $.datepicker.parseDate(dateFormat, element.value);
		} catch(error) {
			date = null;
		}

		return date;
	}
});