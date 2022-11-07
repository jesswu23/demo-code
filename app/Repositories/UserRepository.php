<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $params)
    {
        return User::create($params);
    }
}
