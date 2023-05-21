<?php

namespace App\Services;

use App\Repositories\WalletRepository;
use App\Services\Service;
use Exception;

class WalletService extends Service
{
    public function __construct(
        private WalletRepository $walletRepository
    ){}

    public function create(string $name){
        $name = empty($name) || trim($name) ? $name : now()->timestamp;


    }
}
