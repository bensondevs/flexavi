<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Meta\{AddressController,
    AppointmentController,
    CarController,
    CostController,
    CustomerController,
    EmployeeController,
    ExecuteWorkPhotoController,
    InvoiceController,
    LogController,
    NotificationController,
    OwnerInvitationController,
    PermissionController,
    QuotationController,
    RegisterInvitationController,
    SettingController,
    UserController,
    WorkContractController,
    WorkController,
    WorklistController,
    WorkServiceController
};

/**
 * Work contract meta
 */
Route::group(['prefix' => 'work_contract'], function () {
    Route::get('all_statuses', [WorkContractController::class, 'allStatuses']);
});
/**
 * Address Meta
 */
Route::group(['prefix' => 'worklists'], function () {
    Route::get('all_statuses', [WorklistController::class, 'allWorklistStatuses']);
    Route::get('all_sorting_route_statuses', [WorklistController::class, 'allWorklistSortingRouteStatuses']);
    Route::get('all_always_sorting_route_statuses', [WorklistController::class, 'allWorklistSortingRouteStatuses']);
});

/**
 * Address Meta
 */
Route::group(['prefix' => 'address'], function () {
    Route::get('all_address_types', [AddressController::class, 'allAddressTypes']);
    Route::get('autocomplete', [AddressController::class,'autocomplete']);
});

Route::group(['prefix' => 'customer'], function () {
    Route::get('all_salutation_types', [CustomerController::class, 'allSalutationTypes']);
    Route::get('all_acquisition_types', [CustomerController::class, 'allAcquisitionTypes']);
});


/**
 * Work service Meta
 */
Route::group(['prefix' => 'work_service'], function () {
    Route::get('all_statuses', [WorkServiceController::class, 'allStatuses']);
});


/**
 * Appointment Meta
 */
Route::group(['prefix' => 'appointment'], function () {
    Route::get('all_cancellation_vaults', [AppointmentController::class, 'allCancellationVaults']);
    Route::get('all_statuses', [AppointmentController::class, 'allStatuses']);
    Route::get('all_types', [AppointmentController::class, 'allTypes']);
});

/**
 * Sub Appointment Meta
 */
Route::group(['prefix' => 'sub_appointment'], function () {
    Route::get('all_cancellation_vaults', [AppointmentController::class, 'allCancellationVaults']);
    Route::get('all_statuses', [AppointmentController::class, 'allStatuses']);
});

/**
 * Car Meta
 */
Route::group(['prefix' => 'car'], function () {
    Route::get('all_statuses', [CarController::class, 'allStatuses']);
});

/**
 * Cost Meta
 */
Route::group(['prefix' => 'cost'], function () {
    Route::get('all_costable_types', [CostController::class, 'allCostableTypes']);
});

/**
 * Owner Meta
 */
Route::group(['prefix' => 'owner'], function () {
    Route::get('invitation/statuses', [OwnerInvitationController::class, 'allStatuses']);
});

/**
 * Employee Meta
 */
Route::group(['prefix' => 'employee'], function () {
    Route::get('all_types', [EmployeeController::class, 'allTypes']);
    Route::get('all_employment_statuses', [EmployeeController::class, 'allEmploymentStatuses']);
});

/**
 * Execute Work Photo Meta
 */
Route::group(['prefix' => 'execute_work_photo'], function () {
    Route::get('all_types', [ExecuteWorkPhotoController::class, 'allPhotoConditionTypes']);
});

/**
 * Invoice Meta
 */
Route::group(['prefix' => 'invoice'], function () {
    Route::get('all_statuses', [InvoiceController::class, 'allStatuses']);
    Route::get('selectable_statuses', [InvoiceController::class, 'selectableStatuses']);
    Route::get('all_payment_methods', [InvoiceController::class, 'allPaymentMethods']);
});

/**
 * Quotation Meta
 */
Route::group(['prefix' => 'quotation'], function () {
    Route::get('all_types', [QuotationController::class, 'allTypes']);
    Route::get('all_statuses', [QuotationController::class, 'allStatuses']);
    Route::get('all_payment_methods', [QuotationController::class, 'allPaymentMethods']);
    Route::get('all_damage_causes', [QuotationController::class, 'allDamageCauses']);
    Route::get('all_cancellers', [QuotationController::class, 'allCancellers']);
});

/**
 * Register Invitation Meta
 */
Route::group(['prefix' => 'register_invitation'], function () {
    Route::get('all_statuses', [RegisterInvitationController::class, 'allStatuses']);
});

/**
 * User Meta
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('check_email_used', [UserController::class, 'checkEmailUsed']);
    Route::get('has_company', [UserController::class, 'hasCompany']);
});

/**
 * Work Meta
 */
Route::group(['prefix' => 'work'], function () {
    Route::get('all_statuses', [WorkController::class, 'allStatuses']);
});

/**
 * Setting Meta
 */
Route::group(['prefix' => 'setting'], function () {
    Route::get('all_modules', [SettingController::class, "allModules"]);
});

/**
 * Permissions Meta
 *
 * @see \App\Http\Controllers\Meta\PermissionController
 *      To the route group main controller instance.
 * @see \Tests\Feature\Meta\PermissionTest
 *      To the route group unit tester class.
 */
Route::group(['prefix' => 'permissions'], function () {
    /**
     * Populate permissions for owner role
     *
     * @see \App\Http\Controllers\Meta\PermissionController::owner()
     *      To the route controller method.
     * @see \Tests\Feature\Meta\PermissionTest::test_owner()
     *      To the route unit tester method.
     */
    Route::get('owner', [PermissionController::class, 'owner']);

    /**
     * Populate permissions for employee role
     *
     * @see \App\Http\Controllers\Meta\PermissionController::employee()
     *      To the route controller method.
     * @see \Tests\Feature\Meta\PermissionTest::test_employee()
     *      To the route unit tester method.
     */
    Route::get('employee', [PermissionController::class, 'employee']);
});

/**
 * Log Meta
 */
Route::group(['prefix' => 'log'], function () {
    Route::get('all_action_messages', [LogController::class, "allActionMessages"]);
});


/**
 * Notification Meta
 *
 * @see \App\Http\Controllers\Meta\NotificationController
 *      To the route group main controller instance.
 * @see \Tests\Feature\Meta\NotificationTest
 *      To the route group unit tester class.
 */
Route::group(['prefix' => 'notification'], function () {
    /**
     * Populate types for notification
     *
     * @see \App\Http\Controllers\Meta\NotificationController::allTypes()
     *      To the route controller method.
     * @see \Tests\Feature\Meta\NotificationTest::test_get_all_notification_types()
     *      To the route unit tester method.
     */
    Route::get('all_types', [NotificationController::class, "allTypes"]);

    /**
     * Populate type of notification population types
     *
     * @see \App\Http\Controllers\Meta\NotificationController::allPopulateTypes()
     *      To the route controller method.
     * @see \Tests\Feature\Meta\NotificationTest::test_get_all_notification_populate_types()
     *      To the route unit tester method.
     */
    Route::get('all_populate_types', [NotificationController::class, "allPopulateTypes"]);
});
