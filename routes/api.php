<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WalletHistoryController;
use App\Http\Controllers\Api\Admin\EmailController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\Schedules\FinanceScheduleController;
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
    Route::post("/logout", [AuthController::class, 'logout'])->middleware('api.auth:customer,admin');
    Route::post("/forgot-password", [AuthController::class, 'forgotPassword']);
    Route::post("/validate-code", [AuthController::class, 'validateCode']);
    Route::post("/forgot-password-change", [AuthController::class, 'resetPassword']);
});

Route::group([
    'prefix'=>'categories',
    'middleware' =>  'api.auth:customer,admin'
], function(){
    Route::get("/", [CategoryController::class, 'getAll']);
});

Route::group([
    'prefix'=>'users',
    'middleware' =>  'api.auth:customer,admin'
], function(){
    Route::get("/info", [UserController::class, 'info']);
    Route::put("/info", [UserController::class, 'updateInfo']);
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
        Route::delete("/restore/{walletId}", [WalletHistoryController::class, 'deleteHistory']);
        Route::get("/detail/{historyPk}", [WalletHistoryController::class, 'detail']);
        Route::delete("/delete/{historyPk}", [WalletHistoryController::class, 'deleteMovement']);
        Route::post("/schedule");
        Route::group([
            'prefix'=>'schedule',
        ], function(){
            Route::post("/add", [FinanceScheduleController::class, 'newSchedule']);
            Route::get("/by-wallet/{id}", [FinanceScheduleController::class, 'getByWallet']);
            Route::delete("/delete/{schedulePk}", [FinanceScheduleController::class, 'delete']);

        });
    });
});

Route::group([
    'prefix'=>'loans',
    'middleware' =>  'api.auth:customer,admin'
], function(){
   Route::post("/new", [LoanController::class, 'create']);
   Route::delete("/remove/{loanPk}", [LoanController::class, 'delete']);
   Route::put("/update-status/{loanPk}", [LoanController::class, 'updateStatus']);
   Route::get("/by-user", [LoanController::class, 'getByUser']);
   Route::get("/by-user-type", [LoanController::class, 'getByUserAndType']);
});

Route::group([
    'prefix'=>'countries'
], function(){
    Route::get("/", [CountryController::class, 'getAll']);
});



Route::get('/health', function(){
    return true;
});
