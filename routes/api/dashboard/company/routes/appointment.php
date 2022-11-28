<?php
/**
 * Company Appointment Module
 */

use App\Http\Controllers\Api\Company\Appointments\AppointmentController;
use App\Http\Controllers\Api\Company\Appointments\AppointmentEmployeeController;
use App\Http\Controllers\Api\Company\Appointments\SubAppointmentController;
use App\Http\Controllers\Api\Company\Cost\AppointmentCostController;
use App\Http\Controllers\Api\Company\Work\AppointmentWorkController;
use App\Http\Controllers\Api\Company\Work\SubAppointmentWorkController;
use Illuminate\Support\Facades\Route;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

Route::group(['prefix' => 'appointments'], function () {
    /*
 Route::get('/', [AppointmentController::class, 'companyAppointments']);
     Route::get('unplanneds', [
         AppointmentController::class,
         'unplannedAppointments',
     ]);
     Route::get('trasheds', [
         AppointmentController::class,
         'trashedAppointments',
     ]);
     Route::get('of_customer', [
         AppointmentController::class,
         'customerAppointments',
     ]);
     Route::post('store', [AppointmentController::class, 'store']);
     Route::post('draft', [AppointmentController::class, 'draft']);
     Route::get('view', [AppointmentController::class, 'view']);
     Route::match(['PUT', 'PATCH'], 'update', [
         AppointmentController::class,
         'update',
     ]);
     Route::delete('delete', [AppointmentController::class, 'delete']);
     Route::patch('restore', [AppointmentController::class, 'restore']);

     Route::post('cancel', [AppointmentController::class, 'cancel']);
     Route::post('reschedule', [AppointmentController::class, 'reschedule']);
     Route::post('execute', [AppointmentController::class, 'execute']);
     Route::post('process', [AppointmentController::class, 'process']);
     Route::post('calculate', [AppointmentController::class, 'calculate']);

     Route::post('generate_invoice', [
         AppointmentController::class,
         'generateInvoice',
     ]);

    */

    /**
     * Sub Appointments Module
     */
    Route::group(['prefix' => 'subs'], function () {
        /*
         Route::get('/', [
             SubAppointmentController::class,
             'subAppointments',
         ]);
         Route::post('store', [SubAppointmentController::class, 'store']);
         Route::match(['PUT', 'PATCH'], 'update', [
             SubAppointmentController::class,
             'update',
         ]);
         Route::post('cancel', [SubAppointmentController::class, 'cancel']);
         Route::post('reschedule', [
             SubAppointmentController::class,
             'reschedule',
         ]);
         Route::post('execute', [
             SubAppointmentController::class,
             'execute',
         ]);
         Route::post('process', [
             SubAppointmentController::class,
             'process',
         ]);
         Route::delete('delete', [
             SubAppointmentController::class,
             'delete',
         ]);
        */

        /**
         * Sub Appointment Work Module
         */
        /*
           Route::group(['prefix' => 'works'], function () {
               Route::get('/', [
                   SubAppointmentWorkController::class,
                   'subAppointmentWorks',
               ]);
               Route::post('store', [
                   SubAppointmentWorkController::class,
                   'store',
               ]);
               Route::post('attach', [
                   SubAppointmentWorkController::class,
                   'attach',
               ]);
               Route::post('attach_many', [
                   SubAppointmentWorkController::class,
                   'attachMany',
               ]);
               Route::post('detach', [
                   SubAppointmentWorkController::class,
                   'detach',
               ]);
               Route::post('detach_many', [
                   SubAppointmentWorkController::class,
                   'detachMany',
               ]);
               Route::post('truncate', [
                   SubAppointmentWorkController::class,
                   'truncate',
               ]);
           });
        */
    });

    /**
     * Appointment Employees Module
     */
    /*
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [
            AppointmentEmployeeController::class,
            'appointmentEmployees',
        ]);
        Route::get('trasheds', [
            AppointmentEmployeeController::class,
            'trashedAppointmentEmployees',
        ]);
        Route::post('assign', [
            AppointmentEmployeeController::class,
            'assignEmployee',
        ]);
        Route::post('unassign', [
            AppointmentEmployeeController::class,
            'unassignEmployee',
        ]);
    });
    */

    /**
     * Appointment Work Module
     */
    /*
    Route::group(['prefix' => 'works'], function () {
        Route::get('/', [
            AppointmentWorkController::class,
            'appointmentWorks',
        ]);
        Route::post('store_attach', [
            AppointmentWorkController::class,
            'storeAttach',
        ]);
        Route::post('attach', [AppointmentWorkController::class, 'attach']);
        Route::post('attach_many', [
            AppointmentWorkController::class,
            'attachMany',
        ]);
        Route::post('detach', [AppointmentWorkController::class, 'detach']);
        Route::post('detach_many', [
            AppointmentWorkController::class,
            'detachMany',
        ]);
        Route::post('truncate', [
            AppointmentWorkController::class,
            'truncate',
        ]);
    });
    */

    /**
     * Appointment Cost Module
     */
    /*
    Route::group(['prefix' => 'costs'], function () {
        Route::get('/', [
            AppointmentCostController::class,
            'appointmentCosts',
        ]);
        Route::post('store_record', [
            AppointmentCostController::class,
            'storeRecord',
        ]);
        Route::post('record', [AppointmentCostController::class, 'record']);
        Route::post('record_many', [
            AppointmentCostController::class,
            'recordMany',
        ]);
        Route::post('unrecord', [
            AppointmentCostController::class,
            'unrecord',
        ]);
        Route::post('unrecord_many', [
            AppointmentCostController::class,
            'unrecordMany',
        ]);
        Route::post('truncate', [
            AppointmentCostController::class,
            'truncate',
        ]);
    });
    */
});
