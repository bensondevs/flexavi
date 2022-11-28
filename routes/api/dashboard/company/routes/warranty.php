<?php

use App\Http\Controllers\Api\Company\Warranty\WarrantyController;

/**
 * Company Warranties
 */
Route::group(['prefix' => 'warranties'], function () {
    Route::get('/', [WarrantyController::class, 'companyWarranties']);
    Route::get('/of_employee', [WarrantyController::class, 'employeeWarranties']);
    Route::get('view', [WarrantyController::class, 'view']);
    Route::get('trasheds', [WarrantyController::class, 'trashedWarranties']);
    Route::patch('restore', [WarrantyController::class, 'restore']);
    Route::post('store', [WarrantyController::class, 'store']);
    Route::delete('delete', [WarrantyController::class, 'delete']);
});

