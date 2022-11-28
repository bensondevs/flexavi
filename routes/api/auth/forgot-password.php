<?php

use App\Http\Controllers\Api\Auths\{
    ForgotPasswordController
};

use Illuminate\Support\Facades\Route;

Route::prefix('password')->group(function () {
    Route::post('find', [ForgotPasswordController::class, 'findAccount']);
    Route::post('send_reset_code', [ForgotPasswordController::class, 'sendResetCode']);
    Route::post('validate_token', [ForgotPasswordController::class, 'validateToken']);
    Route::post('reset', [ForgotPasswordController::class, 'resetPassword']);
});
