<?php

use App\Http\Controllers\Api\Company\Invoice\InvoiceController;
use App\Http\Controllers\Api\Company\Invoice\InvoiceLogController;
use App\Http\Controllers\Api\Company\Invoice\InvoiceSettingController;
use Illuminate\Support\Facades\Route;

/**
 * Company Invoice Module
 */
Route::group(['prefix' => 'invoices'], function () {
    Route::get('/', [InvoiceController::class, 'companyInvoices']);
    Route::get('/trasheds', [InvoiceController::class, 'companyTrashedInvoices']);
    Route::post('draft', [InvoiceController::class, 'draft']);
    Route::post('send', [InvoiceController::class, 'send']);
    Route::post('print', [InvoiceController::class, 'print']);
    Route::get('view', [InvoiceController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [InvoiceController::class, 'update']);
    Route::patch('restore', [InvoiceController::class, 'restore']);
    Route::patch('change_status', [InvoiceController::class, 'changeStatus']);
    Route::match(['PUT', 'PATCH'], 'change_status', [InvoiceController::class, 'changeStatus']);
    Route::delete('delete', [InvoiceController::class, 'delete']);

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [InvoiceSettingController::class, 'invoiceSetting']);
        Route::post('save', [InvoiceSettingController::class, 'updateSetting']);
    });

    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', [InvoiceLogController::class, 'invoiceLogs']);
    });
});
