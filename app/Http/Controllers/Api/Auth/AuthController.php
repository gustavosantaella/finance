<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Services\AuthService;
use App\Services\UserService;
use Exception;

class AuthController extends ApiController
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService
    ) {
    }
    public function register()
    {
        $this->req()->validate([
            "email" => ['email', 'required'],
            'password' => ['required', 'min:8'],
            'country' => ['required'],
        ]);
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

    public function login()
    {
        $this->req()->validate([
            "email" => ['email', 'required'],
            'password' => ['required', 'min:8']
        ]);
        ['email' => $email, 'password' => $password] = $this->req()->toArray();
        try {
            $response = $this->userService->login($email, $password);
            return $this->response(
                $response,
            );
        } catch (Exception $e) {
            return $this->response($e);
        }
    }

    public function logout(){
        try{
            $this->userService->logout();
            return $this->response(true);
        }catch(Exception $e){
            return $this->response($e);
        }
    }


    public function forgotPassword(){
        try{
            $data = $this->authService->forgotPassword($this->req()->get('email'));
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }


    public function resetPassword(){
        try{
            $data = $this->authService->resetPassword($this->req()->get('password'), $this->req()->get('email'));
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }

    public function validateCode(){
        try{
            $data = $this->authService->validateCode($this->req()->get('code'));
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }
}
