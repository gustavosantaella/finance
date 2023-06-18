<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Services\WalletService;
use Exception;
use Illuminate\Http\Request;


class WalletController extends ApiController
{
    public function __construct(
        private WalletService $walletService
    ){}

    public function byOwner(){
        try{
            $owner = $this->req()->get('owner') ?? auth()->user()->_id;
            $data = $this->walletService->getByOwner($owner);
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }

    public function balance($walletId){
        try{
            $data = $this->walletService->getBalance($walletId);
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }
}
