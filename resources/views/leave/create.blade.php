@extends('layout')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<main>
	<div class="cotainer">
		<div class="row g-3 justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('leave/store') }}" method="POST" class="form-inline" autocomplete="off">
					@csrf
					<div class="mb-3">
						<label for="start_date" class="form-label">Start date</label>
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01" >
					</div>
					<div class="mb-3">
						<label for="start_time" class="form-label">Start time</label>
						<select class="form-select" aria-label="Default select example" id="start_time" name="start_time">
							<option selected>Please select</option>
							<option value="09:00">09:00 ~ 13:00</option>
							<option value="14:00">14:00 ~ 18:00</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01">
					</div>
					<div class="mb-3">
						<label for="end_time" class="form-label">End time</label>
						<select class="form-select" aria-label="Default select example" id="end_time" name="end_time">
							<option selected>Please select</option>
							<option value="13:00">09:00 ~ 13:00</option>
							<option value="18:00">14:00 ~ 18:00</option>
						</select>
					</div>

					<div class="mb-3 mt-3">
						<label for="type" class="form-label">Type</label>
						<select class="form-select" aria-label="Default select example" id="type" name="type">
							<option selected>Please select</option>
							<option value="1">特休</option>
							<option value="2">事假</option>
							<option value="3">病假</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Reason</label>
						<textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
					</div>
					<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id; }}">

					<button type="submit" id="sendBtn" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</main>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
<script type="text/javascript" src="{{ asset('/js/leave.js') }}"></script>
@endsection