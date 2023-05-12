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
            $this->req()->get("password");
            return $this->response(
                $this->req()->all(),
            );
        } catch (Exception $e) {
            return $this->response($e);
        }
    }
}
