<?php

use App\Http\Controllers\Api\Company\WorkService\WorkServiceController;

/**
 * Company Car Module
 */
Route::group(['prefix' => 'work_services'], function () {
    Route::get('/', [WorkServiceController::class, 'companyWorkServices']);
    Route::get('actives', [WorkServiceController::class, 'activeWorkServices']);
    Route::get('inactives', [WorkServiceController::class, 'inActiveWorkServices']);
    Route::get('trasheds', [WorkServiceController::class, 'trashedWorkServices']);
    Route::post('store', [WorkServiceController::class, 'store']);
    Route::match(['put', 'patch'], 'change_status', [WorkServiceController::class, 'changeStatus']);
    Route::get('view', [WorkServiceController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [WorkServiceController::class, 'update']);
    Route::delete('delete', [WorkServiceController::class, 'delete']);
    Route::patch('restore', [WorkServiceController::class, 'restore']);
});
