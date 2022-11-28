<?php

use App\Http\Controllers\Api\Company\Subscription\{SubscriptionController,
    SubscriptionPlanController,
    SubscriptionPlanPeriodController
};
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'subscriptions'], function () {

    Route::get('/', [SubscriptionController::class, 'companySubscriptions']);
    Route::get('view', [SubscriptionController::class, 'view']);
    Route::post('renew', [SubscriptionController::class, 'renewSubscription']);

    // Subscription Plans
    Route::prefix('plans')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'subscriptionPlans']);
        Route::get('/view', [SubscriptionPlanController::class, 'view']);
        Route::prefix('periods')->group(function () {
            Route::get('/', [SubscriptionPlanPeriodController::class, 'subscriptionPlanPeriods']);
            Route::get('/view', [SubscriptionPlanPeriodController::class, 'view']);
        });
    });
});
