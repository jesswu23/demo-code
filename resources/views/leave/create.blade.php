@extends('layout')
@section('content')
{{-- datepicker --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
<script type="text/javascript" src="{{ asset('/js/leave.js') }}"></script>
<main>
	<div class="cotainer">
		<div class="row g-3 justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('leave/store') }}" method="POST" class="form-inline" autocomplete="off">
					@csrf
					<div class="mb-3">
						<label for="start_date" class="form-label">Start date</label>
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01" >
						@if ($errors->has('start_date'))
						<span class="text-danger">{{ $errors->first('start_date') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="start_time" class="form-label">Start time</label>
						<select class="form-select" aria-label="Default select example" id="start_time" name="start_time">
							<option value="" selected>Please select</option>
							<option value="09:00">09:00 ~ 13:00</option>
							<option value="14:00">14:00 ~ 18:00</option>
						</select>
						@if ($errors->has('start_time'))
						<span class="text-danger">{{ $errors->first('start_time') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01">
						@if ($errors->has('end_date'))
						<span class="text-danger">{{ $errors->first('end_date') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="end_time" class="form-label">End time</label>
						<select class="form-select" aria-label="Default select example" id="end_time" name="end_time">
							<option value="" selected>Please select</option>
							<option value="13:00">09:00 ~ 13:00</option>
							<option value="18:00">14:00 ~ 18:00</option>
						</select>
						@if ($errors->has('end_time'))
						<span class="text-danger">{{ $errors->first('end_time') }}</span>
						@endif
					</div>
					<div class="mb-3 mt-3">
						<label for="type" class="form-label">Type</label>
						<select class="form-select" aria-label="Default select example" id="type" name="type">
							<option value="" selected>Please select</option>
							@foreach ($leaveTypes as $id => $name)
								<option value="{{ $id }}">{{ $name }}</option>
							@endforeach
						</select>
						@if ($errors->has('type'))
						<span class="text-danger">{{ $errors->first('type') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Reason</label>
						<textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
						@if ($errors->has('reason'))
						<span class="text-danger">{{ $errors->first('reason') }}</span>
						@endif
					</div>
					<button type="submit" id="sendBtn" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection