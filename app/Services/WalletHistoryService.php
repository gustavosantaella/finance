<?php

namespace App\Services;

use App\Repositories\WalletHistoryRepository;
use App\Services\Service;
use Exception;

class WalletHistoryService extends Service
{
    public function __construct(
        private WalletHistoryRepository $walletHistoryRepository
    ){}
}
