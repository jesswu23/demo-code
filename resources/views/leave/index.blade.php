@extends('layout')
@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script type="text/javascript" src="https://momentjs.com/downloads/moment.min.js"></script>

<script type="text/javascript" src="{{ asset('/js/calendar.js') }}"></script>
<main>
	<div class="cotainer">
		<div id='calendar'></div>
	</div>
</main>
@endsection