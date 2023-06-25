<?php

namespace App\Services\Wallet;

use Illuminate\Support\Facades\Auth;
use App\Helpers\Log;
use App\Repositories\WalletHistoryRepository;
use App\Repositories\WalletRepository;
use App\Services\Service;
use App\Services\Wallet\WalletOperationService;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Exception;
use WeakReference;

class WalletHistoryService extends Service
{
    private string $total = '0.0';
    private string $incomes = '0.0';
    private string $expenses = '0.0';


    public function __construct(
        private WalletHistoryRepository $walletHistoryRepository,
        private WalletRepository $walletRepository,
        private WalletOperationService $walletOperationService,
        private WalletService $walletService
    ) {
    }


    public function history(string $walletId, $month = null)
    {
        try {

            $history = $this->walletHistoryRepository->getByWallet($walletId, $month);
            $this->total = $this->walletOperationService->financialvalues($history, $this->incomes,$this->expenses);
            $metrics = $this->metricsFromArrayHistory(collect($history)->toArray());
            return [
                "metrics" => $metrics,
                "incomes" => $this->incomes,
                "expenses" => $this->expenses,
                "total" => $this->total,
                "walletId" => $walletId,
                "history" => $history,
                "balance" =>number_format($this->incomes - $this->expenses, 2, '.', '')
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addHistory($payload)
    {
        try {
            $createdBy = array_key_exists('createdBy', $payload)  ? $payload['createdBy']: collect(auth()->user())->toArray() ;
            $this->walletHistoryRepository->add([
                ...$payload,
                "historyId" => now()->timestamp

            ], $createdBy);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }



    public function metricsFromArrayHistory(array $history)
    {
        $total = (float) $this->total;
        $incomes = (float) $this->incomes;
        $expenses = (float) $this->expenses;
        $metrics = [
            "incomes" => 0.0,
            "expenses" => 0.0,
            "barchart" => [],
            "piechart" => [
                "incomes" => [],
                "expenses" => []
            ],

        ];

        $metrics['incomes'] =  !($total < 1)  ? number_format($incomes / $total * 100, 2, '.', '') : '0.0';
        $metrics['expenses'] =   !($total < 1)  ?number_format($expenses / $total * 100, 2, '.', '') : '0.0';
        $carbon = new Carbon();
        $historyByDate = array_map(function ($item) use ($carbon) {
            return [
                ...$item,
                "dateName" =>   $carbon->parse(array_key_exists('created_at', $item) ? $item['created_at'] : $item['createdAt'])->dayName ,
            ];
        }, $history);
        (array) $barChartResult = [];

        foreach ($historyByDate as $value) {
            $key1 = array_search($value['dateName'], array_column($barChartResult, 'dateName'));
            $getValueByType = fn (string $type) => $value['type'] == $type ? floatval(number_format($value['value'],2, '.', '')) : 0.0;
            $incomes = $getValueByType('income');
            $expenses = $getValueByType('expense');
            if (isset($key1) && $key1 > -1) {
                $index = array_search($value['dateName'], array_column($barChartResult, 'dateName'));
                $barChartResult[$index]['income'] += floatval(number_format($incomes,2, '.', ''));
                $barChartResult[$index]['expense'] += floatval(number_format($expenses, 2, '.', ''));
            } else {
                $barChartResult[] = [
                    "date" => array_key_exists('created_at', $value) ? $value['created_at'] :  $value['createdAt'],
                    "dateName" => $value['dateName'],
                    "income" => floatval(floatval(number_format($incomes, 2, '.', ''))),
                    "expense" => floatval(floatval(number_format($expenses, 2, '.', ''))),
                ];
            }
        }
        $pierchartResult = [
            "incomes" => [],
            "expenses" => []
        ];
        foreach ($history as $key => $value) {
            $category = $value['categories']['name'];
            $type = $value['type'];
            $auxType = $value['type'] === 'income' ? 'incomes' : 'expenses';
            $valueMovement = floatval(number_format($value['value'], 2));
            if (count($pierchartResult[$auxType]) > 0) {
                $filtered = collect(array_filter($pierchartResult[$auxType], function ($item) use ($category, $type) {
                    if ($item['category'] == $category && $item['type'] == $type) {
                        return $item;
                    }
                }))->flatMap(function($item){
                   return $item;
                })->toArray();

                if (count($filtered) > 0) {
                    $index = array_search($filtered, $pierchartResult[$auxType]);

                    $pierchartResult[$auxType][$index]['value'] += floatval(number_format($valueMovement,2, '.', ''));
                } else {
                    if ($value['type'] == 'income') {
                        $pierchartResult['incomes'][] = [
                            "category" => $category,
                            "type" => $type,
                            "value" => floatval(number_format($valueMovement,2, '.', ''))
                        ];
                    } else {

                            array_push($pierchartResult[$auxType], [
                                "category" => $category,
                                "type" => $type,
                                "value" => floatval(number_format($valueMovement,2, '.', ''))
                            ]);

                    }
                }
            } else {
                if ($value['type'] == 'income') {
                    $pierchartResult['incomes'][] = [
                        "category" => $category,
                        "type" => $type,
                        "value" => floatval(number_format($valueMovement,2, '.', ''))
                    ];
                } else {
                    $pierchartResult['expenses'][] = [
                        "category" => $category,
                        "type" => $type,
                        "value" => floatval(number_format($valueMovement,2, '.', ''))
                    ];
                }
            }
        }
        $pierchartResult['incomes'] = array_map(function($item){
            return[
                ...$item,
                "value" => number_format($item['value'], 2, '.', '')
            ];

        },$pierchartResult['incomes']);
        $pierchartResult['expenses'] = array_map(function($item){
            return[
                ...$item,
                "value" => number_format($item['value'], 2, '.', '')
            ];

        },$pierchartResult['expenses']);

        $barChartResult = array_map(function($item){
            return[
                ...$item,
                "income" => number_format($item['income'], 2, '.', ''),
                "expense" => number_format($item['expense'], 2, '.', '')
            ];

        },$barChartResult);
        $metrics['barchart'] = $barChartResult;
        $metrics['piechart'] = $pierchartResult;
        return $metrics;
    }

    public function deleteMovement($historyId){
        try{
            Log::write("deleting $historyId");
           $this->walletHistoryRepository->deleteByPk($historyId);
           Log::write("deleted");
            return true;
        }catch(Exception $e){
            Log::write("error");
            Log::write($e->getMessage());

            throw $e;
        }
    }

    public function deleteHistory($walletId){
        try {
            $wallet = $this->walletService->getByPk($walletId);
            Log::write("deleting $wallet");
            if ($wallet->owner != Auth::user()->_id) {
                throw new Exception("You're not allowed to do this.");
            }
            $this->walletHistoryRepository->deleteByWalletId($walletId);
            Log::write("deleted");
            return true;
        }catch (Exception $e) {
            Log::write("error");
            Log::write($e->getMessage());
            throw $e;
        }
    }

    public function detail(string $historyPk){
        try{
            [$detail] = $this->walletHistoryRepository->detail($historyPk);

            return $detail;
        }catch(Exception $e){
            throw $e;
        }
    }
}
