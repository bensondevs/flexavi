<?php

use App\Http\Controllers\Api\Company\PostIt\PostItController;

/**
 * Company Post It Module
 */

Route::group(['prefix' => 'post_its'], function () {
    Route::get('/', [PostItController::class, 'companyPostIts']);
    Route::post('store', [PostItController::class, 'store']);
    Route::match(['PUT', 'PATCH'], 'update', [
        PostItController::class,
        'update',
    ]);
    Route::post('assign_user', [PostItController::class, 'assignUser']);
    Route::post('unassign_user', [PostItController::class, 'unassignUser']);
    Route::delete('delete', [PostItController::class, 'delete']);
    Route::post('restore', [PostItController::class, 'restore']);
});
