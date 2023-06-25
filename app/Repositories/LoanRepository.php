<?php

namespace App\Repositories;

use App\Helpers\Log;
use App\Models\Loan;
use App\Repositories\Repository;

class LoanRepository extends Repository {

    public function __construct(
       private  Loan $model
    ){
        parent::__construct($model);
    }


    public function byUser(string $userPk){
        return $this->model->where("userId", $userPk)->get();
    }
    public function byUserAndType(string $userPk, string $isLoan){

        return $this->model->where("userId", $userPk)->where("isLoan", $isLoan == "true")->get();
    }
}
