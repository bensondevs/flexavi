<?php

namespace App\Providers;

use App\Jobs\Developments\CreateInvoiceLogJob;
use App\Jobs\Developments\CreateQuotationLogJob;
use App\Models\Subscription\Subscription;
use App\Models\User\PersonalAccessToken;
use App\Repositories\Subscription\MolliePlanRepository;
use App\Services\Invoice\InvoiceQueueService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Laravel\Sanctum\Sanctum;
use Queue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(PlanRepository::class, MolliePlanRepository::class);

        // register & set the configuration only on local environment
        if (app()->isLocal()) {
            // register the IDE Helper for local environment
            app()->register(
                \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class
            );
        }

        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });

        Cashier::useSubscriptionModel(Subscription::class);

        Queue::after(function (JobProcessed $event) {
            if (!in_array($event->job->payload()['displayName'], [CreateInvoiceLogJob::class, CreateQuotationLogJob::class])) {
                app(InvoiceQueueService::class)->handle($event->job->payload());
            }
        });
    }
}
