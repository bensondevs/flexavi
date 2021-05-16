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
use App\Http\Controllers\Api\Company\AppointmentController;
use App\Http\Controllers\Api\Company\AppointmentWorkerController;

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
		Company Information
	*/
	Route::group(['prefix' => 'companies'], function () {
		Route::get('user', [CompanyController::class, 'userCompanies']);
		Route::post('update', [CompanyController::class, 'update']);

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
			Route::post('store', [EmployeeController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [EmployeeController::class, 'update']);
			Route::delete('delete', [EmployeeController::class, 'delete']);
		});

		/*
			Company Customer Module
		*/
		Route::group(['prefix' => 'customers'], function () {
			Route::get('/', [CompanyCustomerController::class, 'companyCustomers']);
			Route::post('store', [CompanyCustomerController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [CompanyCustomerController::class, 'update']);
			Route::delete('delete', [CompanyCustomerController::class, 'delete']);
		});

		/*
			Company Car Module
		*/
		Route::group(['prefix' => 'cars'], function () {
			Route::get('/', [CarController::class, 'companyCars']);
			Route::post('store', [CarController::class, 'store']);
			Route::post('set_image', [CarController::class, 'setCarImage']);
			Route::match(['PUT', 'PATCH'], 'update', [CarController::class, 'update']);
			Route::delete('delete', [CarController::class, 'delete']);
		});

		/*
			Company Invoice Module
		*/
		Route::group(['prefix' => 'invoices'], function () {
			Route::get('/', [InvoiceController::class, 'companyInvoices']);
			Route::post('store', [InvoiceController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [InvoiceController::class, 'update']);
			Route::delete('delete', [InvoiceController::class, 'delete']);
		});
	});

	Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

	});

	Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
		Route::get('current', [CustomerController::class, 'current']);
	});
});