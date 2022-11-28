<?php

// Companies meta
use App\Http\Controllers\Api\Company\Meta\CustomerMetaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'meta'], function () {
    Route::get('/customers/cities', [
        CustomerMetaController::class,
        'customerCities',
    ]);
});
