<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;

class RegisterController extends ApiController
{
    public function __construct(
        private UserService $userService
    )
    {
    }
    public function register()
    {
        try {
            ['email' => $email, 'password' => $password, 'country' => $country] = $this->req()->toArray();
            $response = $this->userService->newUser($email, $password, $country);
            return $this->response(
                $response,
            );
        } catch (Exception $e) {
            return $this->response($e);
        }
    }
}
