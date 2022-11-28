<?php
/**
 * Company Owners Module
 */

use App\Http\Controllers\Api\Company\Owner\{
    OwnerController,
    OwnerInvitationController,
    OwnerInvitationPermissionController,
    OwnerPermissionController
};
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'owners'], function () {
    Route::get('/', [OwnerController::class, 'companyOwners']);
    Route::get('inviteables', [OwnerController::class, 'inviteableOwners']);
    Route::get('trasheds', [OwnerController::class, 'trashedOwners']);


    Route::get('view', [OwnerController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [OwnerController::class, 'update']);
    Route::post('set_image', [OwnerController::class, 'setImage']);
    Route::delete('delete', [OwnerController::class, 'delete']);
    Route::match(['PUT','PATCH'], 'main_owner', [OwnerController::class, 'makeAsMainOwner']);
    Route::patch('restore', [OwnerController::class, 'restore']);

    Route::group(['prefix' => 'invitations'], function () {
        Route::get('/', [
            OwnerInvitationController::class,
            'ownerInvitations',
        ]);
        Route::get('view', [OwnerInvitationController::class, 'view']);
        Route::get('accept', [OwnerInvitationController::class, 'accept'])
            ->withoutMiddleware(['auth:sanctum', 'owner', 'has_company']);
        Route::post('store', [
            OwnerInvitationController::class,
            'store',
        ]);
        Route::delete('cancel', [
            OwnerInvitationController::class,
            'cancel',
        ]);
        Route::get('permissions', [OwnerInvitationPermissionController::class, 'permissions']);
    });

    Route::group(['prefix' => 'permissions'], function () {
        /**
         * Get employee permissions collection.
         *
         * @see \Tests\Feature\Dashboard\Company\Owner\OwnerPermissionTest::test_populate_owner_permissions()
         *      To the route unit tester method.
         * @note URL: '/api/dashboard/owners/permissions/'
         */
        Route::get('/', [
            OwnerPermissionController::class,
            'ownerPermissions',
        ]);

        /**
         * Get employee permissions collection.
         *
         * @see \Tests\Feature\Dashboard\Company\Owner\OwnerPermissionTest::test_update_owner_permissions()
         *      To the route unit tester method.
         * @note URL: '/api/dashboard/owners/permissions/update'
         */
        Route::post('/update', [
            OwnerPermissionController::class,
            'update',
        ]);
    });
});
