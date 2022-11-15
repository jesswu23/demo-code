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

	$("#start_time, #end_time").timepicker({
		timeFormat: "HH:mm", // 時間隔式
		interval: 60, //時間間隔
		minTime: "9", //最小時間
		maxTime: "18", //最大時間
		startTime: "9", // 開始時間
		dynamic: false, //是否顯示項目，使第一個項目按時間順序緊接在所選時間之後
		dropdown: true, //是否顯示時間條目的下拉列表
		scrollbar: false //是否顯示捲軸
	});
});