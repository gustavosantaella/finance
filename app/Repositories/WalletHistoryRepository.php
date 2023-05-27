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
            "historyId" => (string) now()->timestamp,
            "createdBy" => count($createdBy) > 0? $createdBy :  [
                ...collect(auth()->user())->toArray()
            ],
            "value" =>(int) $payload['value'],
            "description" => array_key_exists('description', $payload) ? $payload['description'] : ''
        ]);
    }

    public function getByWallet(string $walletId, $month = null){
        $pipeline = [];
        // return $this->model->with('categories')->where("walletId", new ObjectId($walletId))->get();
        if($month){
            $pipeline = [
                [
                    '$match' => [
                        '$expr'=> [
                            '$eq' => [['$month' => '$created_at'], intval($month)]
                ]
                ]
                ]
            ];
        }
        return $this->model->raw(function($query) use($walletId, $pipeline){
            return $query->aggregate([
                ...$pipeline,
                [
                    '$lookup' => [
                        "from" => "categories",
                        "foreignField" => "_id",
                        "localField" => "categoryId",
                        'as'=> "categories",
                    ],
                ],
                [
                    '$unwind' => '$categories'
                ],

                [
                    '$match' =>[
                         "walletId" => new ObjectId($walletId),
                    ]
                ]
            ]);
        });
    }


    public function detail(string $historyPk){
        return $this->model->raw(function($query) use ($historyPk){
            return $query->aggregate([
                [
                    '$match' => [
                        // "walletId"=>new  ObjectId($walletId),
                        "_id"=> new ObjectId($historyPk),
            ]
            ],
                [
                    '$lookup' => [
                        "from"=> "categories",
                        "foreignField"=> "_id",
                        "localField"=> "categoryId",
                        'as' => "categories"
            ]
            ],
                [
                    '$unwind'=> '$categories'
            ],
                [
                    '$set' =>[
                        "categories._id"=>[
                            '$toString' =>'$categories._id'
            ],
                       "walletId"=>[
                            '$toString' =>'$walletId'
                       ],
                       "_id" => [
                            '$toString' =>'$_id'
                       ],
            ]
            ]
            ]);
        });
    }
}
