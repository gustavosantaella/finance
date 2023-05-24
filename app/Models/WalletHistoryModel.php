<?php

namespace App\Models;
use MongoDB\BSON\ObjectId;

class WalletHistoryModel extends BaseMongoModel
{
    protected $collection = 'wallet_history';


    protected $guarded = [];

    protected $casts = [
        "createdBy._id" => 'string'
    ];
    protected function setCreatedByAttribute(array $value){
        $this->attributes['createdBy']  = [
            ...$value,
            "_id" => new ObjectId($value['_id'])
        ];
    }


    // getters

    public function getCreatedByAttribute($value){
        $this->attributes['createdBy'] = [
            ...$value,
            "_id" => $value['_id']['$oid']
        ];
    }

}
