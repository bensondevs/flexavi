<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\Company\CarController;
use App\Http\Controllers\Api\Company\InvoiceController;
use App\Http\Controllers\Api\Company\CompanyController;
use App\Http\Controllers\Api\Company\EmployeeController;
use App\Http\Controllers\Api\Company\CustomerController as CompanyCustomerController;
use App\Http\Controllers\Api\Company\QuotationController;
use App\Http\Controllers\Api\Company\OwnerController;
use App\Http\Controllers\Api\Company\InspectorController;
use App\Http\Controllers\Api\Company\PaymentTermController;
use App\Http\Controllers\Api\Company\AppointmentController;
use App\Http\Controllers\Api\Company\AppointmentWorkerController;
use App\Http\Controllers\Api\Company\RegisterInvitationController;
use App\Http\Controllers\Api\Company\WorkController;
use App\Http\Controllers\Api\Company\WorkContractController;
use App\Http\Controllers\Api\Company\WorkActivityController;
use App\Http\Controllers\Api\Company\WorkConditionPhotoController;

use App\Http\Controllers\Api\Customer\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
	Route::get('check_email_used', [AuthController::class, 'checkEmailUsed']);

	/*
		Conventional Login
	*/
	Route::post('login', [AuthController::class, 'login']);

	/*
		Customer Login
	*/
	Route::group(['prefix' => 'customer'], function () {
		Route::post('login', [AuthController::class, 'customerLogin']);
		Route::post('logout', [AuthController::class, 'customerLogout'])->middleware('auth:sanctum');
	});

	/*
		Social Media Login
	*/
	Route::group(['prefix' => 'socialite'], function () {
		Route::group(['prefix' => 'login'], function () {
			Route::get('{driver}/redirect', [AuthController::class, 'socialMediaLoginRedirect']);
			Route::get('{driver}/callback', [AuthController::class, 'socialMediaLoginCallback']);
		});
		
		Route::group(['prefix' => 'register'], function () {
			Route::get('{driver}/register', [AuthController::class, 'socialMediaRegister']);
		});
	});
	
	/*
		Register
	*/
	Route::post('register', [AuthController::class, 'register']);
	Route::post('register_company', [CompanyController::class, 'registerCompany']);

	Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
	/*
		Current User
	*/
	Route::group(['prefix' => 'user'], function () {
		Route::get('current', [UserController::class, 'current']);
		Route::post('set_profile_picture', [UserController::class, 'setProfilePicture']);
		Route::match(['PUT', 'PATCH'], 'update', [UserController::class, 'update']);
		Route::match(['PUT', 'PATCH'], 'change_password', [UserController::class, 'changePassword']);
	});

	/*
		Company Access for Owner
	*/
	Route::group(['prefix' => 'companies', 'middleware' => ['owner']], function () {
		Route::post('register', [CompanyController::class, 'register']);

		Route::group(['middleware' => ['has_company']], function () {
			Route::get('user', [CompanyController::class, 'userCompany']);
			Route::post('upload_logo', [CompanyController::class, 'uploadCompanyLogo']);
			Route::match(['PUT', 'PATCH'], 'update', [CompanyController::class, 'update']);

			/*
				Company Owners Module
			*/
			Route::group(['prefix' => 'owners'], function () {
				Route::get('/', [OwnerController::class, 'companyOwners']);
				Route::get('inviteables', [OwnerController::class, 'inviteableOwners']);
				Route::get('trasheds', [OwnerController::class, 'trashedOwners']);
				Route::post('store', [OwnerController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [OwnerController::class, 'update']);
				Route::delete('delete', [OwnerController::class, 'delete']);
			});

			/*
				Company Appointment Module
			*/
			Route::group(['prefix' => 'appointments'], function () {
				Route::get('/', [AppointmentController::class, 'companyAppointments']);
				Route::post('store', [AppointmentController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [AppointmentController::class, 'update']);
				Route::delete('delete', [AppointmentController::class, 'delete']);

				Route::group(['prefix' => 'workers'], function () {
					Route::get('/', [AppointmentWorkerController::class, 'companyAppointmentWorkers']);
					Route::post('store', [AppointmentWorkerController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [AppointmentWorkerController::class, 'update']);
					Route::delete('delete', [AppointmentWorkerController::class, 'delete']);
				});
			});

			/*
				Company Quotations Module
			*/
			Route::group(['prefix' => 'quotations'], function () {
				Route::get('/', [QuotationController::class, 'companyQuotations']);
				Route::post('store', [QuotationController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [QuotationController::class, 'update']);
				Route::delete('delete', [QuotationController::class, 'delete']);
			});

			/*
				Company Employee Module
			*/
			Route::group(['prefix' => 'employees'], function () {
				Route::get('/', [EmployeeController::class, 'companyEmployees']);
				Route::get('inviteables', [EmployeeController::class, 'inviteableEmployees']);
				Route::get('trasheds', [EmployeeController::class, 'trashedEmployees']);
				Route::post('store', [EmployeeController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [EmployeeController::class, 'update']);
				Route::delete('delete', [EmployeeController::class, 'delete']);
				Route::patch('restore', [EmployeeController::class, 'restore']);
			});

			/*
				Company Customer Module
			*/
			Route::group(['prefix' => 'customers'], function () {
				Route::get('/', [CompanyCustomerController::class, 'companyCustomers']);
				Route::get('trasheds', [CompanyCustomerController::class, 'trashedCustomers']);
				Route::post('store', [CompanyCustomerController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [CompanyCustomerController::class, 'update']);
				Route::delete('delete', [CompanyCustomerController::class, 'delete']);
				Route::patch('restore', [CompanyCustomerController::class, 'restore']);
			});

			/*
				Company Car Module
			*/
			Route::group(['prefix' => 'cars'], function () {
				Route::get('/', [CarController::class, 'companyCars']);
				Route::get('frees', [CarController::class, 'freeCars']);
				Route::get('trasheds', [CarController::class, 'trashedCars']);
				Route::post('store', [CarController::class, 'store']);
				Route::post('set_image', [CarController::class, 'setCarImage']);
				Route::match(['PUT', 'PATCH'], 'update', [CarController::class, 'update']);
				Route::delete('delete', [CarController::class, 'delete']);
				Route::patch('restore', [CarController::class, 'restore']);
			});

			/*
				Company Inspector Module
			*/
			Route::group(['prefix' => 'inspectors'], function () {
				Route::get('/', [InspectorController::class, 'companyInspectors']);
				Route::post('add', [InspectorController::class, 'add']);
				Route::delete('remove', [InspectorController::class, 'remove']);
			});

			/*
				Company Invoice Module
			*/
			Route::group(['prefix' => 'invoices'], function () {
				Route::get('/', [InvoiceController::class, 'companyInvoices']);
				Route::post('store', [InvoiceController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [InvoiceController::class, 'update']);
				Route::delete('delete', [InvoiceController::class, 'delete']);

				Route::group(['prefix' => 'payment_terms'], function () {
					Route::get('/', [PaymentTermController::class, 'paymentTerms']);
					Route::post('store', [PaymentTermController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [PaymentTermController::class, 'update']);
					Route::delete('delete', [PaymentTermController::class, 'delete']);
				});
			});

			/*
				Company Quotation Module
			*/
			Route::group(['prefix' => 'quotations'], function () {
				Route::get('/', [QuotationController::class, 'companyQuotations']);
				Route::post('store', [QuotationController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [QuotationController::class, 'update']);
				Route::delete('delete', [QuotationController::class, 'delete']);
			});

			/*
				Schedule Module
			*/
			Route::group(['prefix' => 'schedules'], function () {
				Route::get('/', [ScheduleController::class, 'companyWorks']);
				Route::post('store', [ScheduleController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [ScheduleController::class, 'update']);
				Route::delete('delete', [ScheduleController::class, 'delete']);

				/*
					Schedule Car Module
				*/
				Route::group(['prefix' => 'cars'], function () {
					Route::get('/', [ScheduleCarController::class, 'companyScheduleCars']);
					Route::post('store', [ScheduleCarController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [ShceduleCarController::class, 'update']);
					Route::delete('delete', [ScheduleCarController::class, 'delete']);
				});

				/*
					Schedule Employee Module
				*/
				Route::group(['prefix' => 'employees'], function () {
					Route::get('/', [ScheduleEmployeeController::class, 'companyScheduleEmployees']);
					Route::post('store', [ScheduleEmployeeController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [ScheduleEmployeeController::class, 'update']);
					Route::delete('delete', [ScheduleEmployeeController::class, 'delete']);
				});
			});

			/*
				Company Work Module
			*/
			Route::group(['prefix' => 'works'], function () {
				Route::get('/', [WorkController::class, 'companyWorks']);
				Route::post('store', [WorkController::class, 'store']);
				Route::match(['PUT', 'PATCH'], 'update', [WorkController::class, 'update']);
				Route::delete('delete', [WorkController::class, 'delete']);

				/*
					Company Work Contract Module
				*/
				Route::group(['prefix' => 'contract'], function () {
					Route::get('/', [WorkContractController::class, 'companyWorkContracts']);
					Route::post('store', [WorkContractController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [WorkContractController::class, 'update']);
					Route::delete('delete', [WorkContractController::class, 'delete']);
				});

				/*
					Company Work Activity Module
				*/
				Route::group(['prefix' => 'activities'], function () {
					Route::get('/', [WorkActivityController::class, 'companyWorkActivities']);
					Route::post('store', [WorkActivityController::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [WorkActivityController::class, 'update']);
					Route::delete('delete', [WorkActivityController::class, 'delete']);
				});

				/*
					Company Work Condition Photo Module
				*/
				Route::group(['prefix' => 'condition_photos'], function () {
					Route::get('/', [WorkConditionPhotoController::class, 'companyWorkConditionPhotos']);
					Route::post('store', [WorkConditionPhoto::class, 'store']);
					Route::match(['PUT', 'PATCH'], 'update', [WorkConditionPhotoController::class, 'update']);
					Route::delete('delete', [WorkConditionPhotoController::class, 'delete']);
				});
			});

			/*
				Company Register Invitation Module
			*/
			Route::group(['prefix' => 'register_invitations', 'as' => 'register_invitations.'], function () {
				Route::post('invite_employee', [RegisterInvitationController::class, 'inviteEmployee']);
				Route::post('invite_owner', [RegisterInvitationController::class, 'inviteOwner']);
			});
		});
	});

	/*
		Admin Access
	*/
	Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {

	});

	/*
		Customer Access
	*/
	Route::group(['prefix' => 'customer'], function () {
		Route::get('current', [CustomerController::class, 'current']);
	});
});