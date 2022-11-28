<?php

use App\Http\Controllers\Api\Auths\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('verify_email', [AuthController::class, 'verifyEmail'])->name('email_verification');
