<?php

namespace App\Repositories;

use App\Models\BaseMongoModel;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{

    public function __construct(
        private User $user
    ){
        parent::__construct($user);
    }

    public function getAll(){

        return $this->user->all();
    }

    public function getByEmail(String &$email) : ?User {
        return $this->user->where('email', $email)->first();
    }

    public function create(string $email, string $password, string $country, array $roles = []): User{
        return $this->user->create([
            "email" => Str::lower($email),
            "password" => $password,
            "roles" => $roles,
            "country" => $country,
            "created_at" => now(),
        ]);
    }

}
