<?php

namespace App\Repositories;

use App\Models\PasswordResets;
use App\Repositories\Repository;
use Carbon\Carbon;

class PasswordResetRepository extends Repository {

    public function __construct(
        private PasswordResets $model
    ){}

    public function new($email, $code){
        return $this->model->create([
            "token" => $code,
            "email" => $email,
            "expired_at" => now()->addMinutes(5)->timestamp,
        ]);
    }

    public function findByEmail($email){
        return $this->model->where("email", $email)->first();
    }

    public function removeByEmail($email){
        return $this->model->where('email', $email)->delete();
    }

    public function findByCode($code)
    {
        return $this->model->where('token', $code)->first();

    }
}
