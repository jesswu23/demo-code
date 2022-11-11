document.addEventListener('DOMContentLoaded', function () {
	var calendarEl = document.getElementById('calendar');

	var calendar = new FullCalendar.Calendar(calendarEl, {
		timeZone: 'local',
		themeSystem: 'bootstrap5',
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek' // 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
		},
		dayMaxEvents: true, // allow "more" link when too many events
		events: '/leave/events',
		dateClick: function( info ) {
			window.open("/calendar/edit/" + info.dateStr);
		}
	});

	calendar.render();
});