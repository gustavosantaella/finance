<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\WalletRepository;
use App\Services\Service;
use Exception;

class WalletService extends Service
{
    public function __construct(
        private WalletRepository $walletRepository
    ) {
    }

    public function create($owner, $currency)
    {
        try {
            $exists = $this->walletRepository->existWalletByCurrency($owner, $currency);
            if($exists){
                throw new Exception("This currency already exists in your wallets");
            }
            return $this->walletRepository->create(
                $owner,
                $currency
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
