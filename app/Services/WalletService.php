<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\WalletRepository;
use App\Services\Service;
use Exception;

class WalletService extends Service
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletHistoryService $walletHistoryService
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

    public function getByOwner(string $owner): array {
        try{
            $wallets = $this->walletRepository->walletsByOwner($owner)->toArray();
            return $wallets;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getBalance(string $walletId){
        try{
            Log::write(auth()->user()?->email);
            $incomes = 0;
            $expenses = 0;
            $wallet = $this->walletRepository->findOne($walletId);
            $this->walletHistoryService->getTypesValues($incomes, $expenses, $walletId);
            $balance = $incomes - $expenses;
            return [
                "balance" => $balance,
                "expenses" => $expenses,
                "incomes" => $incomes,
                "growthRate" => 0,
                "info" => $wallet
            ];
        }catch(Exception $e){
             throw $e;
        }
    }
}
