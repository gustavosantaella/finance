<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\WalletHistoryRepository;
use App\Repositories\WalletRepository;
use App\Services\Service;
use Exception;

class WalletHistoryService extends Service
{
    public function __construct(
        private WalletHistoryRepository $walletHistoryRepository,
        private WalletRepository $walletRepository
    ){}

    public function history(string $walletId){
        try{

            $history = $this->walletRepository->history($walletId);
            return $history;
        }catch(Exception $e){
            throw $e;
        }
    }
    public function addHistory($payload){
        try{

            $this->walletHistoryRepository->add($payload);
            return true;
        }catch(Exception $e){
            throw $e;
        }
    }
}
