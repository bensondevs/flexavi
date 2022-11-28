<?php

/**
 * Company Employee Module
 */

use App\Http\Controllers\Api\Company\Employee\{
    EmployeeController,
    EmployeeInvitationController,
    EmployeePermissionController,
};
use App\Http\Controllers\Api\Company\Inspection\InspectionController;
use App\Http\Controllers\Api\Company\Quotation\QuotationController;
use App\Http\Controllers\Api\Company\Warranty\WarrantyController;
use App\Http\Controllers\Api\Company\Worklist\WorklistController;
use Illuminate\Support\Facades\Route;

/**
 * Company Employee Module
 */
Route::group(['prefix' => 'employees'], function () {
    Route::get('/', [EmployeeController::class, 'companyEmployees']);
    Route::get('trasheds', [EmployeeController::class, 'trashedEmployees']);
    Route::get('inviteables', [
        EmployeeController::class,
        'inviteableEmployees',
    ]);
    Route::get('view', [EmployeeController::class, 'view']);
    Route::post('store', [EmployeeController::class, 'store']);
    Route::match(['POST','PUT', 'PATCH'], 'update', [EmployeeController::class, 'update']);
    Route::match(['PUT', 'PATCH'], 'update_status', [EmployeeController::class, 'updateStatus']);
    Route::match(['PUT', 'PATCH'], 'reset_password', [EmployeeController::class, 'resetPassword']);
    Route::match(['PUT', 'PATCH'], 'set_image', [EmployeeController::class, 'setImage']);
    Route::delete('delete', [EmployeeController::class, 'delete']);
    Route::match(['PUT', 'PATCH'], 'restore', [
        EmployeeController::class,
        'restore',
    ]);

    // Customer section data
    Route::get('warranties', [WarrantyController::class, 'employeeWarranties']);
    Route::get('quotations', [QuotationController::class, 'employeeQuotations']);
    Route::get('worklists', [WorklistController::class, 'employeeWorklists']);
    Route::get('inspections', [InspectionController::class, 'employeeInspections']);
    Route::get('document', [EmployeeController::class, 'document']);

    // Employee Invitations
    Route::group(['prefix' => 'invitations'], function () {
        Route::get('/', [
            EmployeeInvitationController::class,
            'employeeInvitations',
        ]);
        Route::get('view', [EmployeeInvitationController::class, 'view']);
        Route::get('accept', [
            EmployeeInvitationController::class,
            'accept',
        ])->withoutMiddleware(['auth:sanctum', 'owner', 'has_company']);
        Route::get('action', [
            EmployeeInvitationController::class,
            'handleAction',
        ])->withoutMiddleware(['auth:sanctum', 'owner', 'has_company']);
        Route::post('store', [
            EmployeeInvitationController::class,
            'store',
        ]);
        Route::delete('cancel', [
            EmployeeInvitationController::class,
            'cancel',
        ]);
    });

    /**
     * Employee Permissions Routes
     *
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest
     *      To the main route group unit tester class.
     */
    Route::group(['prefix' => 'permissions'], function () {
        /**
         * Get employee permissions collection.
         *
         * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest::test_populate_employee_permissions()
         *      To the route unit tester method.
         * @note URL: '/api/dashboard/employees/permissions/'
         */
        Route::get('/', [
            EmployeePermissionController::class,
            'employeePermissions',
        ]);

        /**
         * Get employee permissions collection.
         *
         * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest::test_update_employee_permissions()
         *      To the route unit tester method.
         * @note URL: '/api/dashboard/employees/permissions/update'
         */
        Route::post('/update', [
            EmployeePermissionController::class,
            'update',
        ]);
    });
});
