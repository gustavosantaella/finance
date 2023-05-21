<?php

namespace App\Repositories;

use App\Models\Wallet;
use MongoDB\BSON\ObjectId;
class WalletRepository{

    public function __construct(
        private Wallet $model
    ){}

    public function create($name, $owner, $currency){
        return $this->model->create([
            "members" => [],
            "name" => $name,
            "walletId" => now()->timestamp,
            "owner" => new ObjectID($owner),
            "balance" => 0,
            "currency" => $currency
        ]);
    }
}
