@extends('layout')
@section('content')
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
						<input class="form-control" type="text" id="start_date" name="start_date" placeholder="ex:2022-01-01 09:00" value="{{ $leaveList->start_at }}">
					</div>

					<div class="mb-3">
						<label for="end_date" class="form-label">End date</label>
						<input class="form-control" type="text" id="end_date" name="end_date" placeholder="ex:2022-01-01 09:00"  value="{{ $leaveList->end_at }}">
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
@endsection