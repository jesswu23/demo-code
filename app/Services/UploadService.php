<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UploadRepository;

Class UploadService
{
	protected $uploadRepository;

	public function __construct(UploadRepository $uploadRepository)
	{
		$this->uploadRepository = $uploadRepository;
	}

	public function create(array $params)
	{
		return $this->uploadRepository->create([
			'user_id' => Auth::user()->id,
			'file' => $params['file']
		]);
	}

	public function handleUploadedFile(object $uploadFile)
	{
		if (!is_null($uploadFile)) {
			$extension = $uploadFile->extension();
			$dir = '/' . date('Y') . '/' . $extension;
			$fullFilePath = public_path('files') . $dir;
			$relativeFilePath = 'public/files' . $dir;
			$fileName = date('Ymd') . '_' . time() . '.' . $extension;

			// move file
			$uploadFile->move($fullFilePath, $fileName);

			return $this->create(['file' => $relativeFilePath . '/' . $fileName]);
		}
	}
}
