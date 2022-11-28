<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auths\AuthController;
use App\Http\Controllers\Api\Auths\CustomerAuthController;
use App\Http\Controllers\Api\Auths\LoginController;

Route::post('login', [LoginController::class, 'login'])->middleware(["verified"]);

Route::group(['prefix' => 'customer'], function () {
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::post('reset_unique_key', [CustomerAuthController::class, 'resetUniqueKey']);
    Route::post('logout', [CustomerAuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
