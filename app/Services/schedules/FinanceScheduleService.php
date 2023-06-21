<?php

namespace App\Services\Schedules;

use App\Helpers\Date;
use App\Helpers\DateString;
use App\Helpers\Log;
use App\Repositories\Schedules\FinanceScheduleRepository;
use App\Services\Service;
use App\Services\Wallet\WalletService;
use Exception;
use MongoDB\Operation\Aggregate;

class FinanceScheduleService extends Service
{
    public function __construct(
        private FinanceScheduleRepository $financeScheduleRepository,
        private WalletService $walletService
        )
    {
    }

    public function getByWallet($walletId){
        try{

            $data = $this->financeScheduleRepository->getByWallet($walletId);

            return $data;
        }catch(Exception $e){
            throw $e;
        }
    }

    public  function create($data)
    {
        try {
            $schedule = $this->financeScheduleRepository->findByName($data["name"]);
            if($schedule){
                throw new Exception("This schedule already exists");
            }
            $wallet = $this->walletService->getByPk($data['walletId']);
            $scheduleTypes = config("definitions.schedules.finance.types");
            $financeTypes = config("definitions.finance.types");

            $contain = collect($scheduleTypes)->contains($data['periodicity']);
            if (!$contain) {
                throw new Exception("Invalid periodicity");
            }

            $contain = collect($financeTypes)->contains($data['type']);
            if (!$contain) {
                throw new Exception("Invalid type");
            }

            return $this->financeScheduleRepository->create([
                "name" => $data["name"],
                "amount" => (float) $data["amount"],
                "type" => $data["type"],
                "userId" => auth()->user()->_id,
                "walletId" => $wallet->id,
                "startDate" => DateString::now(),
                "periodicity" => $data["periodicity"],
                "categoryId" => $data["categoryId"],
                "nextDate" => DateString::nexDate($data['periodicity']),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($scheduleId){
        try{

            $this->financeScheduleRepository->deleteByPk($scheduleId);
            return true;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function allOfToday(){
        try{
            $today = DateString::now();
            return $this->financeScheduleRepository->whereToday($today);
        }catch(Exception $e){
            throw $e;
        }
    }
}
