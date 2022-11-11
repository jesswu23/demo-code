@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h4>Update calendar</h4>
				<form action="{{ url('/calendar/update/' . $date) }}" method="POST">
					@csrf
					<div class="mb-3">
						<label for="start_date" class="form-label">Date</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $date; }}" disabled>
					</div>

					<div class="mb-3 mt-3">
						<label for="is_holiday" class="form-label">Holiday</label>
						<select class="form-select" aria-label="Default select example" id="is_holiday" name="is_holiday">
							<option @if( $model->is_holiday == '') selected @endif>Please select</option>
							<option @if( $model->is_holiday == '2') selected @endif value="2">Yes</option>
							<option @if( $model->is_holiday == '0') selected @endif value="0">No</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Memo</label>
						<input class="form-control" type="text" id="memo" name="memo" value="{{ $model->memo; }}">
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection