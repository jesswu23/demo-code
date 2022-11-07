<!DOCTYPE html>
<html>
<head>
	<title>{{ __('layout.title') }}</title>
	<!-- js -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
	<!-- css -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/main.css') }}">
</head>
<body>
	<nav class="navbar navbar-light navbar-expand-lg mb-1">
		<div class="container">
			<a class="navbar-brand mr-auto" href="javascript:void(0);">{{ __('layout.title') }}</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					@guest
					<li class="nav-item">
						<a class="nav-link" href="{{ url('login') }}">{{ __('layout.login') }}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ url('registration') }}">{{ __('layout.register') }}</a>
					</li>
					@else
					<li class="nav-item">
						<a class="nav-link" href="{{ url('logout') }}">{{ __('layout.logout') }}</a>
					</li>
					@endguest
				</ul>
			</div>
		</div>
	</nav>
	@if (session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	@if (session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	@yield('content')
</body>
</html>