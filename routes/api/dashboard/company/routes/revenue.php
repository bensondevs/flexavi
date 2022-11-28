<?php

use App\Http\Controllers\Api\Company\Receipt\RevenueReceiptController;
use App\Http\Controllers\Api\Company\Revenue\RevenueController;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

/**
 * Company Revenue Module
 */

/*
Route::group(['prefix' => 'revenues'], function () {
    Route::get('/', [RevenueController::class, 'companyRevenues']);
    Route::post('store', [RevenueController::class, 'store']);
    Route::match(['PUT', 'PATCH'], 'update', [
        RevenueController::class,
        'update',
    ]);
    Route::delete('delete', [RevenueController::class, 'delete']);
    Route::patch('restore', [RevenueController::class, 'restore']);

    Route::group(['prefix' => 'receipt'], function () {
        Route::post('attach', [RevenueReceiptController::class, 'attach']);
        Route::post('replace', [
            RevenueReceiptController::class,
            'replace',
        ]);
    });
});

*/
