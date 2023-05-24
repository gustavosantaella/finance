<?php

namespace App\Models;

class Wallet extends BaseMongoModel
{
    protected $collection = 'wallets';

    protected $guarded = [];


    protected $casts = [
        "name" => "string",
        "walletId" => "string"
    ];

    // Relationships

    public function history(){
        return $this->hasMany(WalletHistoryModel::class, 'walletIds', '_id');
    }

    // public function owner(){
    //     return $this->hasMany(User::class, '_id', 'owner');
    // }


}
