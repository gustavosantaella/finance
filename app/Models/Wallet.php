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

}
