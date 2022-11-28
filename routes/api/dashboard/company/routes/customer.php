<?php

/**
 * Company Customer Module.
 *
 * Base URL: /api/dashboard/companies/customers
 * @TODO Check postman collection and consult with front-end
 */
use App\Http\Controllers\Api\Company\Appointments\AppointmentController;
use App\Http\Controllers\Api\Company\Customer\{
    CustomerController,
    CustomerLogController,
    CustomerNoteController,
};
use App\Http\Controllers\Api\Company\Quotation\QuotationController;
use Illuminate\Support\Facades\Route;

/**
 * @see CustomerController
 *      To the main controller used in this route group.
 * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest
 *      To the route group unit tester class.
 */
Route::group(['prefix' => 'customers'], function () {
    /**
     * @see CustomerController::companyCustomers()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_populate_company_customers()
     *      To the route unit tester method.
     */
    Route::get('/', [CustomerController::class, 'companyCustomers']);

    /**
     * @see CustomerController::trashedCustomers()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_populate_company_trashed_customers()
     *      To the route unit tester method.
     */
    Route::get('trasheds', [CustomerController::class, 'trashedCustomers']);

    /**
     * @see CustomerController::view()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_get_company_customer()
     *      To the route unit tester method.
     */
    Route::get('view', [CustomerController::class, 'view']);

    /**
     * @see CustomerController::store()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_store_company_customer_with_autofill_address()
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_store_company_customer()
     *      To the route unit tester method.
     */
    Route::post('store', [CustomerController::class, 'store']);

    /**
     * @see CustomerController::update()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_update_company_customer()
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_update_company_customer_with_autofill_address()
     *      To the route unit tester method.
     */
    Route::match(['PUT', 'PATCH'], 'update', [CustomerController::class, 'update']);

    /**
     * @see CustomerController::delete()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_delete_company_customer()
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_delete_company_customer_permanently()
     *      To the route unit tester method.
     */
    Route::delete('delete', [CustomerController::class, 'delete']);

    /**
     * @see CustomerController::restore()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest::test_restore_company_trashed_customer()
     *      To the route unit tester method.
     */
    Route::match(['PUT', 'PATCH'], 'restore', [CustomerController::class, 'restore']);

    /**
     * @deprecated Will be released at next development phase.
     */
    Route::get('appointments', [AppointmentController::class, 'customerAppointments']);

    /**
     * @see QuotationController::customerQuotations()
     *      To the used controller method for this route.
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_populate_customer_quotations()
     *      To the route unit tester method.
     */
    Route::get('quotations', [QuotationController::class, 'customerQuotations']);

    /**
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerLogTest
     *      To the route group unit tester class.
     */
    Route::group(['prefix' => 'logs'], function () {
        /**
         * @see CustomerLogController::customerLogs()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerLogTest::test_populate_customer_logs()
         *      To the route unit tester method.
         */
        Route::get('/', [CustomerLogController::class, 'customerLogs']);
    });

    /**
     * @see CustomerNoteController
     *      To the main controller used in this route group.
     * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest
     *      To the route group unit tester class.
     */
    Route::group(['prefix' => 'notes'], function () {
        /**
         * @see CustomerNoteController::customerNotes()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_populate_customer_notes()
         *      To the used controller method for this route.
         */
        Route::get('/', [CustomerNoteController::class, 'customerNotes']);

        /**
         * @see CustomerNoteController::trashedCustomerNotes()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_populate_trashed_customer_notes()
         *      To the used controller method for this route.
         */
        Route::get('trasheds', [CustomerNoteController::class, 'trashedCustomerNotes']);

        /**
         * @see CustomerNoteController::view()
         *      To the used controller method for this route.
         * @deprecated
         */
        Route::get('view', [CustomerNoteController::class, 'view']);

        /**
         * @see CustomerNoteController::store()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_store_customer_note()
         *      To the used controller method for this route.
         */
        Route::post('store', [CustomerNoteController::class, 'store']);

        /**
         * @see CustomerNoteController::update()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_update_customer_note()
         *      To the used controller method for this route.
         */
        Route::put('update', [CustomerNoteController::class, 'update']);

        /**
         * @see CustomerNoteController::delete()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_delete_customer_note()
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_force_delete_customer_note()
         *      To the used controller method for this route.
         */
        Route::delete('delete', [CustomerNoteController::class, 'delete']);

        /**
         * @see CustomerNoteController::restore()
         *      To the used controller method for this route.
         * @see \Tests\Feature\Dashboard\Company\Customer\CustomerNoteTest::test_restore_customer_note()
         *      To the used controller method for this route.
         */
        Route::patch('restore', [CustomerNoteController::class, 'restore']);
    });
});
