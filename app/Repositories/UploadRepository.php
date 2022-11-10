<?php

namespace App\Repositories;

use App\Models\UserFile;

class UploadRepository
{
    public function create(array $params)
    {
        return UserFile::create($params);
    }
}
