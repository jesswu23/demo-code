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
		eventClick: function(info) {
			info.jsEvent.preventDefault(); // don't let the browser navigate

			if (info.event.url) {
				window.open(info.event.url);
			}
		},
		dateClick: function( info ) {
			window.open("/calendar/edit/" + info.dateStr);
		}
	});

	calendar.render();
});