@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
					<h3 class="card-header text-center">User Info</h3>
					<div class="card-body">
						<div class="form-group mb-3">
							Name : {{Auth::user()->name;}}
						</div>
						<div class="form-group mb-3">
							E-mail : {{Auth::user()->email;}}
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="card">
					<h3 class="card-header text-center">Function</h3>
					<div class="card-body">
						<div class="d-grid gap-2 col-6 mx-auto">
							<a href="{{ url('upload') }}" class="btn btn-primary">Upload file</a>
							<a href="{{ url('leave') }}" class="btn btn-primary">Leave</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection