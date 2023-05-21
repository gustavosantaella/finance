<?php

namespace App\Repositories;

use App\Models\BaseMongoModel;
use App\Models\User;

class UserRepository
{

    public function __construct(
        private User $user
    ){}

    public function getAll(){

        return $this->user->all();
    }

    public function getByEmail(String $email) : ?User {
        return $this->user->where('email', $email)->first();
    }

    public function create(string $email, string $password, array $roles = []): User{
        return $this->user->create([
            "email" => $email,
            "password" => $password,
            "roles" => $roles,
            "created_at" => now(),
        ]);
    }
}
