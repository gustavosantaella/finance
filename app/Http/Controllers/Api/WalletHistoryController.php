<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Services\WalletHistoryService;
use Illuminate\Http\Request;


class WalletHistoryController extends ApiController
{
    public function __construct(
        private WalletHistoryService $walletHistoryService
    ){}
   //  Methods
}
