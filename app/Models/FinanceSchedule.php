<?php

namespace App\Models;

class FinanceSchedule extends BaseMongoModel
{
    protected $collection = 'finance_schedules';

    protected $guarded = [];

    public function owner(){
        return $this->hasOne(User::class, "_id", "userId");
    }


}
