<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadService;

class UploadController extends Controller
{
	protected $uploadService;

	public function __construct(UploadService $uploadService)
	{
		$this->uploadService = $uploadService;
	}

	public function uploadFile(Request $request)
	{
		if($request->file('uploadFile')->isValid()) {
			$result = $this->uploadService->handleUploadedFile($request->uploadFile);

			return redirect("upload")->withSuccess('Upload success. file path : ' . $result->file);
		} else {
			return redirect("upload")->withError('Upload failure');
		}
	}
}
