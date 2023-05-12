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
}
