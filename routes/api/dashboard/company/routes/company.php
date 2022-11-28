<?php

use App\Http\Controllers\Api\Company\CompanyController;
use Illuminate\Support\Facades\Route;

Route::post('store', [CompanyController::class, 'store'])->withoutMiddleware('has_company');
Route::post('logo', [CompanyController::class, 'uploadLogo']);
Route::get('self', [CompanyController::class, 'self'])->withoutMiddleware(['has_company', 'owner']);
Route::match(["PUT", "PATCH"], 'update', [CompanyController::class, 'update']);
Route::delete('delete', [CompanyController::class, 'delete']);
Route::patch('restore', [CompanyController::class, 'restore'])->withoutMiddleware('has_company');
