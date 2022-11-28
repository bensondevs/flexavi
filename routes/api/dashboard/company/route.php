<?php

use App\Http\Controllers\Api\Company\{Setting\CompanyController};
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

Route::post('register', [CompanyController::class, 'register']);
Route::group(['middleware' => ['has_company', 'has_dashboard_access']], function () {
    foreach (Finder::create()->in(base_path('routes/api/dashboard/company/routes'))->name('*.php')->exclude(base_path('routes/api/dashboard/company/route.php')) as $file) {
        require $file->getRealPath();
    }
});
