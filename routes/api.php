<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SubscriptionPlanController;

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

Route::group(['prefix' => 'auth'], base_path('routes/api/auth.php'));
Route::group(['prefix' => 'meta'], base_path('routes/api/meta.php'));

Route::get('/subscription_plans', [SubscriptionPlanController::class, 'plans']);

Route::group(
	['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], 
	base_path('routes/api/dashboard.php')
);