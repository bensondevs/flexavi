<?php

use App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'help_desks'], function () {
    Route::get('/', [HelpDeskController::class, 'populate']);
    Route::get('/view', [HelpDeskController::class, 'view']);
    Route::post('/store', [HelpDeskController::class, 'store']);
    Route::match(['PUT' , 'PATCH'], '/update', [HelpDeskController::class, 'update']);
    Route::delete('/delete', [HelpDeskController::class, 'delete']);
});
