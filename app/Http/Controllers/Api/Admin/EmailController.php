<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Services\EmailService;
use Exception;

class EmailController extends ApiController
{
    public function __construct(
        private EmailService $emailService,
    ){}

    public function send(){
        try{
            ['users' => $users, 'data' => $mail] = $this->req()->all();
            return $this->response(true);
        }catch(Exception $e){
            return $this->response($e);
        }
    }
}
