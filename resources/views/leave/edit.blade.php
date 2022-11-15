@extends('layout')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('leave/update') . '/' . $leaveList->id }}" method="POST">
					@method('PUT')
					@csrf
					<div class="mb-3">
						<label for="hours" class="form-label">Apply user</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $leaveList->user->name; }}" disabled>
					</div>

					<div class="mb-3">
						<label for="start_date" class="form-label">Start date</label>
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01" value="{{ $leaveList->start_date }}">
					</div>
					<div class="mb-3">
						<label for="start_time" class="form-label">Start time</label>
						<select class="form-select" aria-label="Default select example" id="start_time" name="start_time">
							<option selected>Please select</option>
							<option value="09:00" @if ($leaveList->start_time == '09:00') selected @endif>09:00 ~ 13:00</option>
							<option value="14:00" @if ($leaveList->start_time == '14:00') selected @endif>14:00 ~ 18:00</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01" value="{{ $leaveList->end_date }}">
					</div>
					<div class="mb-3">
						<label for="end_time" class="form-label">End time</label>
						<select class="form-select" aria-label="Default select example" id="end_time" name="end_time">
							<option selected>Please select</option>
							<option value="13:00" @if ($leaveList->end_time == '13:00') selected @endif>09:00 ~ 13:00</option>
							<option value="18:00" @if ($leaveList->end_time == '18:00') selected @endif>14:00 ~ 18:00</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="hours" class="form-label">Leave hours</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $leaveList->hours; }}" disabled>
					</div>

					<div class="mb-3 mt-3">
						<label for="status" class="form-label">Apply status</label>
						<select class="form-select" aria-label="Default select example" id="status" name="status">
							<option @if ($leaveList->status == '') selected @endif>Please select</option>
							<option value="1" @if ($leaveList->status == '1') selected @endif>申請中</option>
							<option value="2" @if ($leaveList->status == '2') selected @endif>許可</option>
							<option value="3" @if ($leaveList->status == '3') selected @endif>拒絕</option>
						</select>
					</div>

					<div class="mb-3 mt-3">
						<label for="type" class="form-label">Type</label>
						<select class="form-select" aria-label="Default select example" id="type" name="type">
							<option @if ($leaveList->type == '') selected @endif>Please select</option>
							<option value="1" @if ($leaveList->type == '1') selected @endif>特休</option>
							<option value="2" @if ($leaveList->type == '2') selected @endif>事假</option>
							<option value="3" @if ($leaveList->type == '3') selected @endif>病假</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Reason</label>
						<textarea class="form-control" id="reason" name="reason" rows="3">{{ $leaveList->reason}}</textarea>
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