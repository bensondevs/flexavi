<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Meta\CarController;
use App\Http\Controllers\Meta\UserController;
use App\Http\Controllers\Meta\CostController;
use App\Http\Controllers\Meta\WorkController;
use App\Http\Controllers\Meta\InvoiceController;
use App\Http\Controllers\Meta\EmployeeController;
use App\Http\Controllers\Meta\QuotationController;
use App\Http\Controllers\Meta\AppointmentController;
use App\Http\Controllers\Meta\PaymentTermController;
use App\Http\Controllers\Meta\ExecuteWorkPhotoController;
use App\Http\Controllers\Meta\RegisterInvitationController;


/*
	Appointment Meta
*/
Route::group(['prefix' => 'appointment'], function () {
	Route::get('all_cancellation_vaults', [AppointmentController::class, 'allCancellationVaults']);
	Route::get('all_statuses', [AppointmentController::class, 'allStatuses']);
	Route::get('all_types', [AppointmentController::class, 'allTypes']);
});

/*
	Sub Appointment Meta
*/
Route::group(['prefix' => 'sub_appointment'], function () {
	Route::get('all_cancellation_vaults', [AppointmentController::class, 'allCancellationVaults']);
	Route::get('all_statuses', [AppointmentController::class, 'allStatuses']);
});

/*
	Car Meta
*/
Route::group(['prefix' => 'car'], function () {
	Route::get('all_statuses', [CarController::class, 'allStatuses']);
});

/*
	Cost Meta
*/
Route::group(['prefix' => 'cost'], function () {
	Route::get('all_costable_types', [CostController::class, 'allCostableTypes']);
});

/*
	Employee Meta
*/
Route::group(['prefix' => 'employee'], function () {
	Route::get('all_types', [EmployeeController::class, 'allTypes']);
	Route::get('all_employment_statuses', [EmployeeController::class, 'allEmploymentStatuses']);
});

/*
	Execute Work Photo Meta
*/
Route::group(['prefix' => 'execute_work_photo'], function () {
	Route::get('all_types', [ExecuteWorkPhotoController::class, 'allPhotoConditionTypes']);
});

/*
	Invoice Meta
*/
Route::group(['prefix' => 'invoice'], function () {
	Route::get('all_statuses', [InvoiceController::class, 'allStatuses']);
	Route::get('selectable_statuses', [InvoiceController::class, 'selectableStatuses']);
	Route::get('all_payment_methods', [InvoiceController::class, 'allPaymentMethods']);
});

/*
	Payment Term Meta
*/
Route::group(['prefix' => 'payment_term'], function () {
	Route::get('all_statuses', [PaymentTermController::class, 'allStatuses']);
});

/*
	Quotation Meta
*/
Route::group(['prefix' => 'quotation'], function () {
	Route::get('all_types', [QuotationController::class, 'allTypes']);
	Route::get('all_statuses', [QuotationController::class, 'allStatuses']);
	Route::get('all_payment_methods', [QuotationController::class, 'allPaymentMethods']);
	Route::get('all_damage_causes', [QuotationController::class, 'allDamageCauses']);
	Route::get('all_canceller', [QuotationController::class, 'allCanceller']);
});

/*
	Register Invitation Meta
*/
Route::group(['prefix' => 'register_invitation'], function () {
	Route::get('all_statuses', [RegisterInvitationController::class, 'allStatuses']);
});

/*
	User Meta
*/
Route::group(['prefix' => 'user'], function () {
	Route::get('check_email_used', [UserController::class, 'checkEmailUsed']);
	Route::get('all_id_card_types', [UserController::class, 'allIdCardTypes']);
});

/*
	Register Invitation Meta
*/
Route::group(['prefix' => 'work'], function () {
	Route::get('all_statuses', [WorkController::class, 'allStatuses']);
});