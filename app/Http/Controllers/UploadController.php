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

	public function index()
	{
		return view('upload.index');
	}

	public function uploadFile(Request $request)
	{
		if($request->file('uploadFile')->isValid()) {
			$upload = $this->uploadService->handleUploadedFile($request->uploadFile);

			return redirect("upload")->with('success', 'Upload success. file path : ' . $upload->file);
		} else {
			return redirect("upload")->with('error', 'Upload failure');
		}
	}
}
