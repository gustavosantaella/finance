<?php

namespace App\Services\Wallet;

use App\Helpers\Number;
use App\Repositories\WalletHistoryRepository;
use App\Repositories\WalletRepository;
use Exception;

class WalletOperationService
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletHistoryRepository $walletHistoryRepository
    ){}

    public function getTypesValues(&$incomes, &$expenses, $walletId){
        try{
             $history = $this->walletHistoryRepository->getByWallet($walletId);
             $this->financialvalues($history, $incomes, $expenses);

        }catch(Exception $e){
            throw $e;
        }
    }
    public function getBalance($walletId){
        $incomes = 0;
        $expenses = 0;
        $wallet = $this->walletRepository->findOne($walletId);
        $this->getTypesValues($incomes, $expenses, $walletId);
        $balance = $incomes - $expenses;
        return [
            "balance" => Number::formatDecimal($balance),
            "expenses" => Number::formatDecimal($expenses),
            "incomes" => Number::formatDecimal($incomes),
            "growthRate" => 0,
            "info" => $wallet
        ];
    }

    public function financialvalues($history, &$incomes, &$expenses): string{
        $getHistoryValue = fn (string $type) => array_map(function ($item) use ($type) {
            return $item['type'] == $type ? $item['value'] : 0;
        }, collect($history)->toArray());
        $incomes = (number_format(array_sum($getHistoryValue('income')), 2, '.', ''));
        $expenses = number_format(array_sum($getHistoryValue('expense')),2, '.', '');
        return (number_format((float)$incomes + (float)$expenses,2, '.', ''));
    }
}
