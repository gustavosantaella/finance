<?php

namespace App\Services\Wallet;

use App\Helpers\Log;
use App\Helpers\Number;
use App\Repositories\WalletRepository;
use App\Services\Service;
use App\Services\Wallet\WalletOperationService;
use Exception;

class WalletService extends Service
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletOperationService $walletOperationService
    ) {
    }

    public function create($owner, $currency)
    {
        try {
            $exists = $this->walletRepository->existWalletByCurrency($owner, $currency);
            if ($exists) {
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

    public function getByOwner(string $owner): array
    {
        try {
            $wallets = $this->walletRepository->walletsByOwner($owner)->toArray();
            return $wallets;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getBalance(string $walletId)
    {
        try {
            return $this->walletOperationService->getBalance($walletId);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getByPk(string $pk)
    {
        try {
            Log::write($pk);
            $data = $this->walletRepository->findOne($pk);
            if (!$data) {
                throw new Exception("Wallet not found");
            }
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
