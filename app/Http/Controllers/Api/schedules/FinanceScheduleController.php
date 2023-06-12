<?php
namespace App\Http\Controllers\Api\Schedules;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Services\Schedules\FinanceScheduleService;
use Exception;

class FinanceScheduleController extends ApiController {

    public function __construct(
        private FinanceScheduleService $financeScheduleService
    ){}

    public function newSchedule(){
        try{
            $data = $this->financeScheduleService->create($this->req()->all());
            return $this->response($data);
        }catch(Exception $e){

            return $this->response($e);
        }
    }
    public function delete($schedulePk){
        try{
            $data = $this->financeScheduleService->delete($schedulePk);
            return $this->response($data);
        }catch(Exception $e){

            return $this->response($e);
        }
    }


    public function getByWallet($id){
        try{
            $data = $this->financeScheduleService->getByWallet($id);
            return $this->response($data);
        }catch(Exception $e){

            return $this->response($e);
        }
    }


}
