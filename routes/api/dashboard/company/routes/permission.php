<?php

use App\Http\Controllers\Api\Company\Permission\PermissionController;

/**
 * Permissions Module
 */

Route::group(['prefix' => 'permissions'], function () {
    Route::get('/of_userable', [PermissionController::class, 'userablePermissions']);
});
