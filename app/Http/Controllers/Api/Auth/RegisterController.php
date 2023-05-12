<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Repositories\UserRepository;
use Exception;

class RegisterController extends ApiController
{
    public function __construct()
    {
    }
    public function register()
    {

        try {
            return $this->response(
                [],
            );
        } catch (Exception $e) {
            return $this->response($e);
        }
    }
}
