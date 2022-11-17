@extends('layout')
@section('content')
<main>
	<div class="cotainer">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<form action="{{ url('upload/upload_file') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="mb-3">
						<label for="uploadFile" class="form-label">Upload file</label>
						<input class="form-control" type="file" id="uploadFile" name="uploadFile">
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection