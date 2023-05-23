<?php

namespace App\Repositories;

use App\Models\Wallet;
use MongoDB\BSON\ObjectId;
class WalletRepository{

    public function __construct(
        private Wallet $model
    ){}

    public function create(string $owner, string $currency){
        $walletId  = now()->timestamp;
        return $this->model->create([
            "members" => [],
            "name" => $walletId,
            "walletId" => $walletId,
            "owner" => new ObjectId($owner),
            "balance" => 0,
            "currency" => $currency
        ]);
    }

    public function existWalletByCurrency(string $owner, string $currency){
        return $this->model->where('owner', new ObjectId($owner))->where('currency', $currency)->exists();
    }

    public function walletsByOwner(string $owner){
        return $this->model->where('owner', new ObjectId($owner))->get();
    }

    public function findOne(string $walletId){
        return $this->model->find($walletId);
    }
}
