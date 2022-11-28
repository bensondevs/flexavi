<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            $this->getApiRoutes();

            // Web Route
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1200)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Get API Routes
     *
     * @return void
     */
    protected function getApiRoutes(): void
    {

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        // Authentication Socialite Authentication Route
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/auth/socialite.php'));

        // Authentication Forgot Password Route
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/auth/forgot-password.php'));

        // Authentication Login Route
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/auth/login.php'));

        // Authentication Register Route
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/auth/register.php'));

        // Meta Route
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/meta.php'));

        // Third party API callback
        Route::namespace($this->namespace)
            ->group(base_path('routes/api/thirdparty_callback.php'));

    }
}
