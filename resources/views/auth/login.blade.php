@extends('layout')
@section('content')
<main class="login-form">
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
					<h3 class="card-header text-center">{{ __('user.login') }}</h3>
					<div class="card-body">
						<form method="POST" action="{{ url('custom-login') }}">
							@csrf
							<div class="form-group mb-3">
								<input type="text" placeholder="Email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
								@if ($errors->has('email'))
								<span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
							</div>
							<div class="form-group mb-3">
								<input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
								@if ($errors->has('password'))
								<span class="text-danger">{{ $errors->first('password') }}</span>
								@endif
							</div>
							<div class="d-grid mx-auto">
								<button type="submit" class="btn btn-dark btn-block">{{ __('user.signin') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection