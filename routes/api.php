<?php

use App\Http\Controllers\Api\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

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
    foreach (Finder::create()->in(base_path('routes/api/auth'))->name('*.php') as $file) require $file->getRealPath();
});

Route::group(['prefix' => 'meta'], base_path('routes/api/meta.php'));

Route::group(['prefix' => 'third_party_callbacks'], base_path('routes/api/thirdparty_callback.php'));

Route::get('/subscription_plans', [SubscriptionPlanController::class, 'plans']);

Route::group(
    ['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'],
    base_path('routes/api/dashboard.php')
);
