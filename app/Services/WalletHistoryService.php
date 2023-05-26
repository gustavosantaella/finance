<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\WalletHistoryRepository;
use App\Repositories\WalletRepository;
use App\Services\Service;
use Carbon\Carbon;
use Exception;

class WalletHistoryService extends Service
{
    private float $total = 0.0;
    private float $incomes = 0.0;
    private float $expenses = 0.0;
    public function __construct(
        private WalletHistoryRepository $walletHistoryRepository,
        private WalletRepository $walletRepository
    ) {
    }

    public function financialvalues($history){
        $getHistoryValue = fn (string $type) => array_map(function ($item) use ($type) {
            return $item['type'] == $type ? $item['value'] : 0;
        }, collect($history)->toArray());
        $this->incomes = floatval(number_format(array_sum($getHistoryValue('income')), 2));
        $this->expenses = floatval(number_format(array_sum($getHistoryValue('expense')), 2));
        $this->total = floatval(number_format($this->incomes + $this->expenses,2));
    }
    public function history(string $walletId, $month = null)
    {
        try {

            $history = $this->walletHistoryRepository->getByWallet($walletId, $month);
            $this->financialvalues($history);
            $metrics = $this->metricsFromArrayHistory(collect($history)->toArray());
            return [
                "metrics" => $metrics,
                "incomes" => $this->incomes,
                "expenses" => $this->expenses,
                "total" => $this->total,
                "walletId" => $walletId,
                "history" => $history,
                "balance" => $this->incomes - $this->expenses
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addHistory($payload)
    {
        try {

            $this->walletHistoryRepository->add($payload);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getTypesValues(&$incomes, &$expenses, $walletId){
        try{
             $history = $this->walletHistoryRepository->getByWallet($walletId);
             $this->financialvalues($history);
             $incomes = $this->incomes;
             $expenses = $this->expenses;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function metricsFromArrayHistory(array $history)
    {
        $metrics = [
            "incomes" => 0.0,
            "expenses" => 0.0,
            "barchart" => [],
            "piechart" => [
                "incomes" => [],
                "expenses" => []
            ],

        ];

        $metrics['incomes'] =  !($this->total < 1)  ? number_format($this->incomes / $this->total * 100, 2) : 0.0;
        $metrics['expenses'] =   !($this->total < 1)  ?number_format($this->expenses / $this->total * 100, 2) : 0.0;
        $carbon = new Carbon();
        $historyByDate = array_map(function ($item) use ($carbon) {
            return [
                ...$item,
                "dateName" => $carbon->parse($item['created_at'])->dayName,
            ];
        }, $history);
        (array) $barChartResult = [];

        foreach ($historyByDate as $value) {
            $key1 = array_search($value['dateName'], array_column($barChartResult, 'dateName'));
            $getValueByType = fn (string $type) => $value['type'] == $type ? number_format($value['value'],2) : 0.0;
            $incomes = $getValueByType('income');
            $expenses = $getValueByType('expense');
            if (isset($key1) && $key1 > -1) {
                $index = array_search($value['dateName'], array_column($barChartResult, 'dateName'));
                $barChartResult[$index]['income'] += number_format($incomes,2);
                $barChartResult[$index]['expense'] += number_format($expenses, 2);
            } else {
                $barChartResult[] = [
                    "date" => $value['created_at'],
                    "dateName" => $value['dateName'],
                    "income" => floatval(number_format($incomes, 2)),
                    "expense" => floatval(number_format($expenses, 2)),
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
            $valueMovement = number_format($value['value'], 2);
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

                    $pierchartResult[$auxType][$index]['value'] += number_format($valueMovement,2);
                } else {
                    if ($value['type'] == 'income') {
                        $pierchartResult['incomes'][] = [
                            "category" => $category,
                            "type" => $type,
                            "value" => number_format($valueMovement,2)
                        ];
                    } else {

                            array_push($pierchartResult[$auxType], [
                                "category" => $category,
                                "type" => $type,
                                "value" => number_format($valueMovement,2)
                            ]);

                    }
                }
            } else {
                if ($value['type'] == 'income') {
                    $pierchartResult['incomes'][] = [
                        "category" => $category,
                        "type" => $type,
                        "value" => number_format($valueMovement,2)
                    ];
                } else {
                    $pierchartResult['expenses'][] = [
                        "category" => $category,
                        "type" => $type,
                        "value" => number_format($valueMovement,2)
                    ];
                }
            }
        }
        $metrics['barchart'] = $barChartResult;
        $metrics['piechart'] = $pierchartResult;
        return $metrics;
    }
}
