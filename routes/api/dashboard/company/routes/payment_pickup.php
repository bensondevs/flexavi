<?php


use App\Http\Controllers\Api\Company\PaymentPickup\PaymentPickupController;

/**
 * Company Payment Pickups
 */
Route::group(['prefix' => 'payment_pickups'], function () {
    Route::get('/', [PaymentPickupController::class, 'companyPaymentPickups']);
    Route::get('/trasheds', [PaymentPickupController::class, 'paymentPickupTrasheds']);
    Route::get('view', [PaymentPickupController::class, 'view']);
    Route::post('store', [PaymentPickupController::class, 'store']);

    Route::delete('delete', [PaymentPickupController::class, 'delete']);
    Route::patch('restore', [PaymentPickupController::class, 'restore']);
});

