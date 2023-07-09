<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'access.token'], function () {
    // Routes that require the access token to be checked
    Route::controller(UserController::class)->group(
        function () {
            Route::get('getUserDetails', 'getUserDetails');
            Route::post('transactions', 'transactions');
            Route::get('getCustomerDetails', 'getCustomerDetails');
            Route::post('getTransactionDetails','getCustomerTransactions');
        }
    );
});

Route::controller(UserController::class)->group(
    function () {
        Route::post('login', 'login');
    }
);
