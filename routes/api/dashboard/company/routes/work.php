<?php


/**
 * Company work module
 */

use App\Http\Controllers\Api\Company\Work\WorkController;

Route::group(['prefix' => 'works'], function () {
    Route::get('/', [WorkController::class, 'companyWorks']);
    Route::get('appointment_finisheds', [
        WorkController::class,
        'appointmentFinishedWorks',
    ]);
    Route::get('finisheds', [WorkController::class, 'finishedWorks']);
    Route::get('unfinisheds', [WorkController::class, 'unfinishedWorks']);
    Route::get('trasheds', [WorkController::class, 'trashedWorks']);
    Route::post('store', [WorkController::class, 'store']);
    Route::get('view', [WorkController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [
        WorkController::class,
        'update',
    ]);
    Route::delete('delete', [WorkController::class, 'delete']);
    Route::patch('restore', [WorkController::class, 'restore']);


    Route::post('process', [WorkController::class, 'process']);
    Route::post('mark_finish', [WorkController::class, 'markFinish']);
    Route::post('mark_unfinish', [WorkController::class, 'markUnfinsih']);
});
