@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
					<h3 class="card-header text-center">{{ __('user.info') }}</h3>
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
		</div>
	</div>
</main>
@endsection