<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

Class UserService
{
    protected $userRepository;

    public function __construct( UserRepository $userRepository ) {
        $this->userRepository = $userRepository;
    }

    public function create(array $params)
    {
        return $this->userRepository->create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => Hash::make($params['password'])
        ]);
    }
}
