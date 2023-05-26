<?php

namespace App\Repositories;

use App\Models\WalletHistoryModel;
use MongoDB\BSON\ObjectId;

class WalletHistoryRepository{

    public function __construct(
        private WalletHistoryModel $model
    ){}

    public function add(array $payload, array $createdBy = []){
        return $this->model->create([
            "walletId" => new ObjectId($payload['walletId']),
            "categoryId" => new ObjectId($payload['categoryId']),
            "type" => $payload['type'],
            "gateway" => [
                "provider" => $payload['provider'],
                "data" => [],
            ],
            "hisotyId" => (string) now()->timestamp,
            "createdBy" => count($createdBy) > 0? $createdBy :  [
                ...collect(auth()->user())->toArray()
            ],
            "value" =>(int) $payload['value'],
            "description" => array_key_exists('description', $payload) ? $payload['description'] : ''
        ]);
    }

    public function getByWallet(string $walletId){
        return $this->model->with('categories')->where("walletId", new ObjectId($walletId))->get();
    }
}
