@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h4>Update calendar</h4>
				<form action="{{ url('/calendar/update/' . $date) }}" method="POST">
					@method('PUT')
					@csrf
					<div class="mb-3">
						<label for="start_date" class="form-label">Date</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $date; }}" disabled>
					</div>
					<div class="mb-3 mt-3">
						<label for="is_holiday" class="form-label">Holiday</label>
						<select class="form-select" aria-label="Default select example" id="is_holiday" name="is_holiday">
							<option value="" @selected($calendar && $calendar->is_holiday == '')>Please select</option>
							<option value="2" @selected($calendar && $calendar->is_holiday == '2')>Yes</option>
							<option value="0" @selected($calendar && $calendar->is_holiday == '0')>No</option>
						</select>
						@if ($errors->has('is_holiday'))
						<span class="text-danger">{{ $errors->first('is_holiday') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Memo</label>
						<input class="form-control" type="text" id="memo" name="memo" value="@if ($calendar){{ $calendar->memo; }}@endif">
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection