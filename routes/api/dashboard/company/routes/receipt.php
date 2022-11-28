<?php

use App\Http\Controllers\Api\Company\Receipt\ReceiptController;

/**
 * Company Receipt Module
 */
Route::group(['prefix' => 'receipts'], function () {
    Route::get('/', [ReceiptController::class, 'receipts']);
    Route::get('trasheds', [ReceiptController::class, 'trashedReceipts']);
    Route::post('store', [ReceiptController::class, 'store']);
    Route::match(['PUT', 'PATCH'], 'update', [
        ReceiptController::class,
        'update',
    ]);
    Route::delete('delete', [ReceiptController::class, 'delete']);
    Route::patch('restore', [ReceiptController::class, 'restore']);
});
