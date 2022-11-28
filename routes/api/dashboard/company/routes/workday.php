<?php

use App\Http\Controllers\Api\Company\Cost\WorkdayCostController;
use App\Http\Controllers\Api\Company\Workday\WorkdayController;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

/**
 * Company Workday Module
 */
/*
Route::group(['prefix' => 'workdays'], function () {
    Route::get('/', [WorkdayController::class, 'companyWorkdays']);
    Route::get('current', [WorkdayController::class, 'currentWorkday']);
    Route::get('view', [WorkdayController::class, 'view']);
    Route::post('process', [WorkdayController::class, 'process']);
    Route::post('calculate', [WorkdayController::class, 'calculate']);
    Route::delete('delete', [WorkdayController::class, 'delete']);
    Route::post('restore', [WorkdayController::class, 'restore']);
    Route::get('trasheds', [WorkdayController::class, 'trasheds']);

    Route::group(['prefix' => 'costs'], function () {
        Route::get('/', [WorkdayCostController::class, 'workdayCosts']);
        Route::post('store_record', [
            WorkdayCostController::class,
            'storeRecord',
        ]);
        Route::post('record', [WorkdayCostController::class, 'record']);
        Route::post('record_many', [
            WorkdayCostController::class,
            'recordMany',
        ]);
        Route::post('unrecord', [WorkdayCostController::class, 'unrecord']);
        Route::post('unrecord_many', [
            WorkdayCostController::class,
            'unrecordMany',
        ]);
        Route::post('truncate', [WorkdayCostController::class, 'truncate']);
    });
});
*/
