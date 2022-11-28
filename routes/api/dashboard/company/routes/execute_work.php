<?php

use App\Http\Controllers\Api\Company\ExecuteWork\ExecuteWorkController;

Route::group(['prefix' => 'execute_works'], function () {
    Route::get('/', [ExecuteWorkController::class, 'companyExecuteWorks']);
    Route::get('trasheds', [
        ExecuteWorkController::class,
        'trashedExecuteWorks',
    ]);
    Route::get('of_customer', [
        ExecuteWorkController::class,
        'customerExecuteWorks',
    ]);
    Route::post('store', [ExecuteWorkController::class, 'store']);
    Route::get('view', [ExecuteWorkController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [
        ExecuteWorkController::class,
        'update',
    ]);
    Route::delete('delete', [ExecuteWorkController::class, 'delete']);
    Route::patch('restore', [ExecuteWorkController::class, 'restore']);
});
