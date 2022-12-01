@extends('layout')
@section('content')
{{-- datepicker --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
<script type="text/javascript" src="{{ asset('/js/leave.js') }}"></script>
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('leave/update') . '/' . $leave->id }}" method="POST">
					@method('PUT')
					@csrf
					<div class="mb-3">
						<label for="hours" class="form-label">Apply user</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $leave->user->name; }}" disabled>
					</div>
					<div class="mb-3">
						<label for="start_date" class="form-label">Start date</label>
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01" value="{{ $leave->start_date }}">
						@if ($errors->has('start_date'))
						<span class="text-danger">{{ $errors->first('start_date') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="start_time" class="form-label">Start time</label>
						<select class="form-select" aria-label="Default select example" id="start_time" name="start_time">
							<option selected>Please select</option>
							<option value="09:00" @selected($leave->start_time == '09:00')>09:00 ~ 13:00</option>
							<option value="14:00" @selected($leave->start_time == '14:00')>14:00 ~ 18:00</option>
						</select>
						@if ($errors->has('start_time'))
						<span class="text-danger">{{ $errors->first('start_time') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01" value="{{ $leave->end_date }}">
						@if ($errors->has('end_date'))
						<span class="text-danger">{{ $errors->first('end_date') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="end_time" class="form-label">End time</label>
						<select class="form-select" aria-label="Default select example" id="end_time" name="end_time">
							<option selected>Please select</option>
							<option value="13:00" @selected($leave->end_time == '13:00')>09:00 ~ 13:00</option>
							<option value="18:00" @selected($leave->end_time == '18:00')>14:00 ~ 18:00</option>
						</select>
						@if ($errors->has('end_time'))
						<span class="text-danger">{{ $errors->first('end_time') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="hours" class="form-label">Leave hours</label>
						<input class="form-control" type="text" aria-label="Disabled input example" value="{{ $leave->hours; }}" disabled>
					</div>
					<div class="mb-3 mt-3">
						<label for="status" class="form-label">Apply status</label>
						<select class="form-select" aria-label="Default select example" id="status" name="status">
							<option value=""  @selected($leave->status == '')>Please select</option>
							<option value="1" @selected($leave->status == '1')>申請中</option>
							<option value="2" @selected($leave->status == '2')>許可</option>
							<option value="3" @selected($leave->status == '3')>拒絕</option>
						</select>
						@if ($errors->has('status'))
						<span class="text-danger">{{ $errors->first('status') }}</span>
						@endif
					</div>
					<div class="mb-3 mt-3">
						<label for="type" class="form-label">Type</label>
						<select class="form-select" aria-label="Default select example" id="type" name="type">
							<option value="" @selected($leave->type == '')>Please select</option>
							@foreach ($leaveTypes as $id => $name)
								<option value="{{ $id }}" @selected($leave->type == $id)>{{ $name }}</option>
							@endforeach
						</select>
						@if ($errors->has('type'))
						<span class="text-danger">{{ $errors->first('type') }}</span>
						@endif
					</div>
					<div class="mb-3">
						<label for="reason" class="form-label">Reason</label>
						<textarea class="form-control" id="reason" name="reason" rows="3">{{ $leave->reason }}</textarea>
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