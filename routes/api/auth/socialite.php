<?php

use App\Http\Controllers\Api\Auths\Socialite\Google\{GoogleLoginController, GoogleRegisterController};
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'socialite'], function () {
    Route::group(['prefix' => 'register'], function () {
        Route::get('google/redirect', [GoogleRegisterController::class, 'redirect']);
        Route::get('google/callback', [GoogleRegisterController::class, 'callback']);
    });
    Route::group(['prefix' => 'login'], function () {
        Route::get('google/redirect', [GoogleLoginController::class, 'redirect']);
        Route::get('google/callback', [GoogleLoginController::class, 'callback']);
    });
});
