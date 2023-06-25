<?php

namespace App\Services\Loan;

use App\Repositories\LoanRepository;
use App\Services\Category\CategoryService;
use App\Services\Service;
use App\Services\Wallet\WalletHistoryService;
use App\Services\Wallet\WalletService;
use Exception;

class LoanService extends Service
{
    public function __construct(
        private LoanRepository $loanRepository,
        private CategoryService $categoryService,
        private WalletService $walletService,
        private WalletHistoryService $walletHistoryService
    ) {
    }

    public function create(array $payload)
    {
        try {
            $wallet = $this->walletService->getByPk($payload['walletPk']);
            $category = $this->categoryService->getCategory($payload['categoryPk']);
            if ($payload['isLoan'] === true) {
                $this->walletHistoryService->addHistory([
                    "value" => $payload['amount'],
                    "description" => in_array('description', $payload) ? $payload['description'] : "",
                    "categoryId" => $category->_id,
                    "walletId" => $wallet->_id,
                    "type" => config('definitions.finance.types')[1],
                    "provider" => "WAFI",
                ]);
            }
            $data = $this->loanRepository->createData([...$payload, 'userId' => auth()->user()->_id]);
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getByWallet()
    {
    }

    public function byUserAndType(string $isLoan)
    {
        try {
            return $this->loanRepository->byUserAndType(auth()->user()->_id, $isLoan);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getByuser()
    {
        try {
            return $this->loanRepository->byUser(auth()->user()->_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deletePk(string $loanPk)
    {
        try {
            $this->loanRepository->deleteByPk($loanPk);
        } catch (Exception $e) {
            throw  $e;
        }
    }

    public function updateStatus($status, $loanPk)
    {
        try {
            if (!in_array($status, config('definitions.loans.types'))) {
                throw new Exception("$status isn't a valid status");
            }
            $loan = $this->loanRepository->find($loanPk);
            if (!$loan) {
                throw new Exception("Loan not exists");
            }
            if($loan->status === 'paid'){
                throw new Exception('This loan already payed');
            }
            if ($status == 'paid') {
                $type = config('definitions.finance.types')[0];
                if ($loan->isLoan !== true) {
                    $type = config('definitions.finance.types')[1];
                }

                $this->walletHistoryService->addHistory([
                    "value" => $loan->amount,
                    "description" => $loan?->description,
                    "categoryId" => $loan->categoryPk,
                    "walletId" => $loan->walletPk,
                    "type" => $type,
                    "provider" => "WAFI",
                ]);
            }
            $this->loanRepository->update($loanPk, ["status" => $status]);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
