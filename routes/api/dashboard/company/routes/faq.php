<?php

use App\Http\Controllers\Api\Company\FAQ\FAQController;

/**
 * FAQ Module
 */
Route::group(['prefix' => 'faqs'], function () {
    Route::get('/', [FAQController::class, 'index']);
    Route::get('/view', [FAQController::class, 'view']);
});
