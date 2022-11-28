<?php

/**
 * Company Worklist Module
 */

use App\Http\Controllers\Api\Company\Cost\WorklistCostController;
use App\Http\Controllers\Api\Company\Worklist\WorklistAppointmentController;
use App\Http\Controllers\Api\Company\Worklist\WorklistController;
use Illuminate\Support\Facades\Route;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

Route::group(['prefix' => 'worklists'], function () {
    /*
     Route::get('/', [WorklistController::class, 'companyWorklists']);
     Route::get('trasheds', [WorklistController::class, 'trashedWorklists']);
     Route::get('of_workday', [
         WorklistController::class,
         'workdayWorklists',
     ]);
     Route::get('of_employee', [
         WorklistController::class,
         'employeeWorklists',
     ]);
     Route::get('view', [WorklistController::class, 'view']);
     Route::get('trasheds/view', [WorklistController::class, 'trashedView']);
     Route::post('store', [WorklistController::class, 'store']);
     Route::post('process', [WorklistController::class, 'process']);
     Route::post('route', [WorklistController::class, 'route']);
     Route::post('calculate', [WorklistController::class, 'calculate']);
     Route::post('move', [WorklistController::class, 'move']);
     Route::match(['PUT', 'PATCH'], 'update', [
         WorklistController::class,
         'update',
     ]);
     Route::match(['PUT', 'PATCH'], 'restore', [
         WorklistController::class,
         'restore',
     ]);
     Route::delete('delete', [WorklistController::class, 'delete']);
    */

    /**
     * Worklist Cost Module
     */
    /*
    Route::group(['prefix' => 'costs'], function () {
        Route::get('/', [WorklistCostController::class, 'worklistCosts']);
        Route::post('store_record', [
            WorklistCostController::class,
            'storeRecord',
        ]);
        Route::post('record', [WorklistCostController::class, 'record']);
        Route::post('record_many', [
            WorklistCostController::class,
            'recordMany',
        ]);
        Route::post('unrecord', [
            WorklistCostController::class,
            'unrecord',
        ]);
        Route::post('unrecord_many', [
            WorklistCostController::class,
            'unrecordMany',
        ]);
        Route::post('truncate', [
            WorklistCostController::class,
            'truncate',
        ]);
    });
    */

    /**
     * Worklist Appointment Module
     */
    /*
     Route::group(['prefix' => 'appointments'], function () {
         Route::get('/', [
             WorklistAppointmentController::class,
             'worklistAppointments',
         ]);
         Route::post('attach', [
             WorklistAppointmentController::class,
             'attach',
         ]);
         Route::post('attach_many', [
             WorklistAppointmentController::class,
             'attachMany',
         ]);
         Route::post('move', [WorklistAppointmentController::class, 'move']);
         Route::post('detach', [
             WorklistAppointmentController::class,
             'detach',
         ]);
         Route::post('detach_many', [
             WorklistAppointmentController::class,
             'detachMany',
         ]);
         Route::post('truncate', [
             WorklistAppointmentController::class,
             'truncate',
         ]);
     });
    */
});
