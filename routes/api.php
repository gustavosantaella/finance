<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WalletHistoryController;
use App\Http\Middleware\Api\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    "prefix"=>'auth'
], function(){
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login']);
});

Route::group([
    'prefix'=>'categories',
    'middleware' =>  'api.auth:customer,admin'
], function(){
    Route::get("/", [CategoryController::class, 'getAll']);
});

Route::group([
    'prefix'=>'wallet',
    'middleware' =>  'api.auth:customer,admin'
], function(){
    Route::get("/by-owner", [WalletController::class, 'byOwner']);
    Route::get("/{walletId}", [WalletController::class, 'balance']);
    Route::group([
        'prefix'=>'history',
    ], function(){
        Route::get("/{walletId}", [WalletHistoryController::class, 'getHistory']);
        Route::post("/", [WalletHistoryController::class, 'add']);
        Route::get("/detail/{historyPk}", [WalletHistoryController::class, 'detail']);
    });
});

Route::group([
    'prefix'=>'countries'
], function(){
    Route::get("/", [CountryController::class, 'getAll']);
});

Route::get('/health', function(){
    return true;
});
