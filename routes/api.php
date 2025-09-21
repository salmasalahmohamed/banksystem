<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (){

    Route::post('register',[\App\Http\Controllers\AuthenticationController::class,'register']);
    Route::post('login',[\App\Http\Controllers\AuthenticationController::class,'login']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('logout',[\App\Http\Controllers\AuthenticationController::class,'logout']);

    });

});
Route::middleware('auth:sanctum')->group(function (){
    Route::post('set/pin',[\App\Http\Controllers\PinController::class,'setPin']);
    Route::post('validate/pin',[\App\Http\Controllers\PinController::class,'validatePin']);
    Route::post('accountnumber',[\App\Http\Controllers\AccountController::class,'store']);
    Route::post('deposit',[\App\Http\Controllers\DepositController::class,'store']);

    Route::post('withdraw',[\App\Http\Controllers\WithdrawController::class,'store']);
    Route::post('transfer',[\App\Http\Controllers\TransferController::class,'store']);
    Route::post('history',[\App\Http\Controllers\TransactionController::class,'index']);


});
