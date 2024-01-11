<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandAmbassadorController;
use App\Http\Controllers\TeamLeader\TeamLeaderController;
use App\Models\User;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/auth', [AuthController::class, 'checkLogin']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => 'role:'.User::BRAND_AMBASSADOR], function () {
        Route::get('/sales', [BrandAmbassadorController::class, 'getSales']);
        Route::post('/tracking', [BrandAmbassadorController::class, 'locationStore']);
        Route::get('/schedule', [BrandAmbassadorController::class, 'scheduling'])->name('api.schedule');
        Route::get('/schedule/{deployment}/accept', [BrandAmbassadorController::class, 'schedulingAccept']);
        Route::get('/schedule/{deployment}/decline', [BrandAmbassadorController::class, 'schedulingDecline']);
        Route::get('/transactions', [BrandAmbassadorController::class, 'transactions']);
        Route::get('/transactions/{uuid}', [BrandAmbassadorController::class, 'transactionsShow']);
        Route::get('/accounts', [BrandAmbassadorController::class, 'accounts']);
        Route::get('/warehouse-items', [BrandAmbassadorController::class, 'warehouseItems']);
        Route::put('/transactions', [BrandAmbassadorController::class, 'transactionsStore']);
    });
});

Route::post('/login', [AuthController::class, 'login']);

