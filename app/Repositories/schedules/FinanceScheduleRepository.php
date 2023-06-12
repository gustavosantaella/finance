<?php

namespace App\Repositories\Schedules;

use App\Helpers\Log;
use App\Models\FinanceSchedule;
use App\Repositories\Repository;

class FinanceScheduleRepository extends Repository
{
    public function __construct(private FinanceSchedule $model ){
        parent::__construct($model);
    }

    public  function create($data){
        return $this->model->create($data);
    }

    public  function findByName(string $name){
        return $this->model->where('name', $name)->first();
    }

    public function getByWallet($walletId){
        return $this->model->where("walletId", $walletId)->get();
    }

    public function whereToday($dateString){
        return $this->model->where("startDate", "<=", $dateString)->where("nextDate", "=", $dateString)->get();
    }
}
