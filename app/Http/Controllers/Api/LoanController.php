<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\LoanRequest;
use App\Services\Loan\LoanService;
use Exception;
use Illuminate\Http\Request;

class LoanController extends ApiController
{
    public function __construct(
        private LoanService $loanService,
    ){}
   //  Methods

   public function create(LoanRequest $request){
    try{
        $data = $this->loanService->create($request->all());
        return $this->response($data);
    }catch(Exception $e){
        return $this->response($e);
    }
   }

   public function delete(string $loanPk){
    try{
        $this->loanService->deletePk($loanPk);
        return $this->response(true);
    }catch(Exception $e){
        return $this->response($e);
    }
   }

   public function getByUserAndType(Request $request){
    try{
       $data=  $this->loanService->byUserAndType($request->type);
        return $this->response($data);
    }catch(Exception $e){
        return $this->response($e);
    }
   }

   public function updateStatus(Request $request, string $loanPk){
    try{
        $this->loanService->updateStatus($request->status, $loanPk);
        return $this->response(true);
    }catch(Exception $e){
        return $this->response($e);
    }
   }

   public function getByUser(){
    try{
        $data = $this->loanService->getByUser();
        return $this->response($data);
    }catch(Exception $e){
        return $this->response($e);
    }
   }
}
