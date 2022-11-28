<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group(
    ['prefix' => 'companies', 'middleware' => ['has_company']],
    base_path('routes/api/dashboard/company/route.php')
);

/*
	Admin Access
*/
Route::group(
    ['prefix' => 'admin', 'middleware' => ['admin']],
    base_path('routes/api/dashboard/admin/route.php')
);

/*
	Customer Access
*/
Route::group(
    ['prefix' => 'customer'],
    base_path('routes/api/dashboard/customer/route.php')
);
