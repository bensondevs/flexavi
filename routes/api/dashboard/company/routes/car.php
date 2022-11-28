<?php

use App\Http\Controllers\Api\Company\Car\CarController;
use App\Http\Controllers\Api\Company\Car\CarRegisterTimeController;
use App\Http\Controllers\Api\Company\Car\CarRegisterTimeEmployeeController;
use Illuminate\Support\Facades\Route;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

/**
 * Company Car Module
 */
Route::group(['prefix' => 'cars'], function () {
    /*
     Route::get('/', [CarController::class, 'companyCars']);
     Route::get('frees', [CarController::class, 'freeCars']);
     Route::get('trasheds', [CarController::class, 'trashedCars']);
     Route::post('store', [CarController::class, 'store']);
     Route::post('update', [CarController::class, 'update']);
     Route::post('set_image', [CarController::class, 'setCarImage']);
     Route::get('view', [CarController::class, 'view']);
     Route::delete('delete', [CarController::class, 'delete']);
     Route::match(['PUT', 'PATCH'], 'restore', [
         CarController::class,
         'restore',
     ]);
    */

    /**
     * Company Car Register Time Module
     */
    Route::group(['prefix' => 'register_times'], function () {
        /*
        Route::get('/', [
            CarRegisterTimeController::class,
            'carRegisterTimes',
        ]);
        Route::post('register', [
            CarRegisterTimeController::class,
            'registerTime',
        ]);
        Route::post('register_to_worklist', [
            CarRegisterTimeController::class,
            'registerToWorklist',
        ]);
        Route::delete('unregister', [
            CarRegisterTimeController::class,
            'unregister',
        ]);
        Route::match(['PUT', 'PATCH'], 'update', [
            CarRegisterTimeController::class,
            'update',
        ]);
        Route::match(['PUT', 'PATCH'], 'mark_out', [
            CarRegisterTimeController::class,
            'markOut',
        ]);
        Route::match(['PUT', 'PATCH'], 'mark_return', [
            CarRegisterTimeController::class,
            'markReturn',
        ]);
        */

        /**
         * Company Car Register Time Employees
         */
        /*
         Route::group(['prefix' => 'assigned_employees'], function () {
             Route::get('/', [
                 CarRegisterTimeEmployeeController::class,
                 'assignedEmployees',
             ]);
             Route::get('show', [
                 CarRegisterTimeEmployeeController::class,
                 'show',
             ]);
             Route::post('assign', [
                 CarRegisterTimeEmployeeController::class,
                 'assignEmployee',
             ]);
             Route::delete('unassign', [
                 CarRegisterTimeEmployeeController::class,
                 'unassignEmployee',
             ]);
             Route::match(['PUT', 'PATCH'], 'set_as_driver', [
                 CarRegisterTimeEmployeeController::class,
                 'setAsDriver',
             ]);
             Route::match(['PUT', 'PATCH'], 'set_out', [
                 CarRegisterTimeEmployeeController::class,
                 'setOut',
             ]);
         });
        */
    });
});
