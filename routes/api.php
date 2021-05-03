<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\Company\CompanyController;
use App\Http\Controllers\Api\Company\CarController;

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
			Company Car Module
		*/
		Route::group(['prefix' => 'cars'], function () {
			Route::get('/', [CarController::class, 'companyCars']);
			Route::post('store', [CarController::class, 'store']);
			Route::post('set_image', [CarController::class, 'setCarImage']);
			Route::match(['PUT', 'PATCH'], 'update', [CarController::class, 'update']);
			Route::delete('delete', [CarController::class, 'delete']);
		});
	});

	Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

	});

	Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
		Route::get('current', [CustomerController::class, 'current']);
	});
});