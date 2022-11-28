<?php

/**
 * Company Quotation Module
 */

use App\Http\Controllers\Api\Company\Quotation\QuotationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'quotations'], function () {
    Route::get('/', [QuotationController::class, 'companyQuotations']);
    Route::get('/logs', [QuotationController::class, 'quotationLogs']);
    Route::get('trasheds', [QuotationController::class, 'trashedQuotations']);
    Route::get('of_customer', [QuotationController::class, 'customerQuotations']);
    Route::get('of_employee', [QuotationController::class, 'employeeQuotations']);
    Route::post('draft', [QuotationController::class, 'draft']);
    Route::post('store', [QuotationController::class, 'store']);
    Route::post('print', [QuotationController::class, 'print']);
    Route::post('send', [QuotationController::class, 'send']);
    Route::get('view', [QuotationController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [QuotationController::class, 'update']);

    /**
     * Company Quotation Module Actions
     */
    Route::patch('restore', [QuotationController::class, 'restore']);
    Route::patch('nullify', [QuotationController::class, 'nullify']);
    Route::delete('delete', [QuotationController::class, 'delete']);
    Route::post('generate_invoice', [QuotationController::class, 'generateInvoice']);

    Route::group(['prefix' => 'signed_document'], function () {
        Route::post('upload', [QuotationController::class, 'saveSignedDoc']);
        Route::delete('delete', [QuotationController::class, 'removeSignedDoc']);
    });
});
