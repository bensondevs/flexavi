<?php

use App\Http\Controllers\Api\Auths\RegisterController;
use App\Http\Controllers\Api\Company\Setting\CompanyController;
use Illuminate\Support\Facades\Route;


Route::post('register', [RegisterController::class, 'register']);
Route::post('register_company', [CompanyController::class, 'registerCompany']);
Route::get('registration_code', [RegisterController::class, 'findInvitationCode']);
