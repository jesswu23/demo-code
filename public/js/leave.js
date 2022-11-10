$(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('input[name="_token"]').val()
		}
	});

	$('#sendBtn').click(function(event) {

		/* Act on the event */

		var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
		var type = $("#type").val();
		var reason = $("#reason").val();
		var user_id = $("#user_id").val();

		$.post('/api/store', {start_date: start_date, end_date: end_date, type: type, reason: reason, user_id: user_id}, function(data, textStatus, xhr) {
			/*optional stuff to do after success */
			if(data.status == 'error') {
				alert(data.message);
			} else {
				alert('insert success');
			}
		}, 'json');
	});
});