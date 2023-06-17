<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Services\UserService;
use Exception;

class UserController extends ApiController
{
    public function __construct(
        private UserService $userService
    ){}
   //  Methods

   public function info(){
    try{
        $data = $this->userService->getInfo();
        return $this->response($data);
    }catch(Exception $e){
        return $this->response($e);
    }
   }

   public function updateInfo(){
    try{
        $data = $this->userService->updateInfo($this->req()->all());
        return $this->response($data);
    }catch(Exception $e){
        return $this->response($e);
    }
   }
}
