<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\WalletRepository;
use App\Services\WalletHistoryService;
use Exception;
use Illuminate\Http\Request;


class WalletHistoryController extends ApiController
{
    public function __construct(
        private WalletHistoryService $walletHistoryService,
    ){}


    public function getHistory(string $walletId){
        try{
            $month = $this->req()->get('month') ?? null;
            $data = $this->walletHistoryService->history($walletId, $month);
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }

    public function add(){
        try{
            $data = $this->walletHistoryService->addHistory($this->req()->all());
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }

    public function detail(string $historyPk){
        try{
            $data= $this->walletHistoryService->detail($historyPk);
            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }
   //  Methods
}
