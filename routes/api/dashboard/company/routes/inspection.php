<?php

use App\Http\Controllers\Api\Company\Inspection\InspectionController;


/**
 * Company Quotation Module
 */
Route::group(['prefix' => 'inspections'], function () {
    Route::get('/', [InspectionController::class, 'companyInspections']);
    Route::get('of_employee', [
        InspectionController::class,
        'employeeInspections',
    ]);
    Route::get('trasheds', [
        InspectionController::class,
        'trashedInspections',
    ]);
    Route::get('of_customer', [
        InspectionController::class,
        'customerInspections',
    ]);
    Route::post('store', [InspectionController::class, 'store']);
    Route::get('view', [InspectionController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [
        InspectionController::class,
        'update',
    ]);
    Route::delete('delete', [InspectionController::class, 'delete']);
    Route::patch('restore', [InspectionController::class, 'restore']);
});

