<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends BaseMongoModel
{

    protected $collection = 'loans';

    protected $guarded = [];


    protected $attributes = [
        'status' => 'pending'
    ];

}
