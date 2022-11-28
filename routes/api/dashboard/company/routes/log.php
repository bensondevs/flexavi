<?php

use App\Http\Controllers\Api\Company\Log\LogController;

/**
 * Log History Module
 */

Route::group(['prefix' => 'logs'], function () {
    Route::get('/', [LogController::class, "companyLogs"]);
    Route::get('trasheds', [LogController::class, 'trashedLogs']);
    Route::match(['PUT', 'PATCH'], '/restore', [LogController::class, "restore"]);
    Route::delete('/delete', [LogController::class, "delete"]);
});
