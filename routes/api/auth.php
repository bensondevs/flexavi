<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auths\AuthController;
use App\Http\Controllers\Api\Auths\CustomerAuthController;
use App\Http\Controllers\Api\Company\CompanyController;


/*
	Conventional Login
*/
Route::post('login', [AuthController::class, 'login']);

/*
	Customer Login
*/
Route::group(['prefix' => 'customer'], function () {
	Route::post('login', [CustomerAuthController::class, 'login']);
	Route::post('reset_unique_key', [CustomerAuthController::class, 'resetUniqueKey']);
	Route::post('logout', [CustomerAuthController::class, 'logout'])->middleware('auth:sanctum');
});

/*
	Social Media Login
*/
Route::group(['prefix' => 'socialite'], function () {
	Route::group(['prefix' => 'login'], function () {
		Route::get('{driver}/redirect', [AuthController::class, 'socialMediaLoginRedirect']);
		Route::get('{driver}/callback', [AuthController::class, 'socialMediaLoginCallback']);
	});
	
	Route::group(['prefix' => 'register'], function () {
		Route::get('{driver}', [AuthController::class, 'socialMediaRegister']);
	});
});

/*
	Register
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('register_company', [CompanyController::class, 'registerCompany']);

/*
	Verify Email
*/
Route::get('verify_email', [AuthController::class, 'verifyEmail']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');