@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('leave/store') }}" method="POST">
					@csrf
					<div class="mb-3">
						<label for="start_date" class="form-label">Start date</label>
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01 09:00">
					</div>

					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01 09:00">
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
@endsection