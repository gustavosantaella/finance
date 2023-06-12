<?php

namespace App\Jobs;

use App\Helpers\Log;
use App\Services\Schedules\FinanceScheduleService;
use App\Services\WalletHistoryService;
use App\Services\WalletService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinanceSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private WalletService $walletService,
        private FinanceScheduleService $financeScheduleService,
        private WalletHistoryService $walletHistoryService
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $schedules = $this->financeScheduleService->allOfToday();
        //

        foreach ($schedules as $schedule) {

           try{
            $user = $schedule->owner()->first();
            $this->walletHistoryService->addHistory([
                "walletId" => $schedule['walletId'],
                "categoryId" => $schedule['categoryId'],
                "type" => $schedule['type'],
                "provider" => "WAFI",
                "value" => (float) $schedule['amount'],
                "description" => $schedule['name'],
                "createdBy" => $user->toArray()
            ]);
           }catch(Exception $e){

           }
        }
    }
}
