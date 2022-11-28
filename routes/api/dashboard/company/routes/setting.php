<?php

/**
 * Setting Module
 */

use App\Http\Controllers\Api\Company\Invoice\InvoiceSettingController;
use App\Http\Controllers\Api\Company\Setting\CompanySettingController;
use App\Http\Controllers\Api\Company\Setting\CustomerSettingController;
use App\Http\Controllers\Api\Company\Setting\DashboardSettingController;
use App\Http\Controllers\Api\Company\Setting\EmployeeSettingController;
use App\Http\Controllers\Api\Company\Setting\QuotationSettingController;
use App\Http\Controllers\Api\Company\Setting\WorkContractSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardSettingController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [DashboardSettingController::class, 'update']);
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeSettingController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [EmployeeSettingController::class, 'update']);
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerSettingController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [CustomerSettingController::class, 'update']);
    });

    Route::prefix('invoice')->group(function () {
        Route::get('/', [InvoiceSettingController::class, 'invoiceSetting']);
        Route::get('/update', [InvoiceSettingController::class, 'updateSetting']);
    });

    Route::prefix('quotation')->group(function () {
        Route::get('/', [QuotationSettingController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [QuotationSettingController::class, 'update']);
    });

    Route::prefix('company')->group(function () {
        Route::get('/', [CompanySettingController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [CompanySettingController::class, 'update']);
    });

    Route::prefix('work_contract')->group(function () {
        Route::get('/', [WorkContractSettingController::class, 'workContract']);
        Route::match(['PUT', 'PATCH'], '/update', [WorkContractSettingController::class, 'update']);
    });
});
