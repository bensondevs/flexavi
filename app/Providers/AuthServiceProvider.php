<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Policies\WorkPolicy;
use App\Policies\QuotationPolicy;
use App\Policies\AppointmentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Appointment
        Gate::define('view-any-appointment', [AppointmentPolicy::class, 'viewAny']);
        Gate::define('view-appointment', [AppointmentPolicy::class, 'view']);
        Gate::define('create-appointment', [AppointmentPolicy::class, 'create']);
        Gate::define('generate-invoice', [AppointmentPolicy::class, 'generateInvoice']);
        Gate::define('update-appointment', [AppointmentPolicy::class, 'update']);
        Gate::define('cancel-appointment', [AppointmentPolicy::class, 'cancel']);
        Gate::define('execute-appointment', [AppointmentPolicy::class, 'execute']);
        Gate::define('process-appointment', [AppointmentPolicy::class, 'process']);
        Gate::define('calculate-appointment', [AppointmentPolicy::class, 'calculate']);
        Gate::define('delete-appointment', [AppointmentPolicy::class, 'delete']);
        Gate::define('restore-appointment', [AppointmentPolicy::class, 'restore']);
        Gate::define('force-delete-appointment', [AppointmentPolicy::class, 'forceDelete']);

        // Quotation
        Gate::define('view-any-quotation', [QuotationPolicy::class, 'viewAny']);
        Gate::define('view-quotation', [QuotationPolicy::class, 'view']);
        Gate::define('create-quotation', [QuotationPolicy::class, 'create']);
        Gate::define('create-quotation-with-appointment', [QuotationPolicy::class, 'createWithAppointment']);
        Gate::define('update-quotation', [QuotationPolicy::class, 'update']);
        Gate::define('send-quotation', [QuotationPolicy::class, 'send']);
        Gate::define('revise-quotation', [QuotationPolicy::class, 'revise']);
        Gate::define('honor-quotation', [QuotationPolicy::class, 'honor']);
        Gate::define('cancel-quotation', [QuotationPolicy::class, 'cancel']);
        Gate::define('delete-quotation', [QuotationPolicy::class, 'delete']);
        Gate::define('restore-quotation', [QuotationPolicy::class, 'restore']);
        Gate::define('force-delete-quotation', [QuotationPolicy::class, 'forceDelete']);

        // Work
        Gate::define('view-any-work', [WorkPolicy::class, 'viewAny']);
        Gate::define('view-work', [WorkPolicy::class, 'view']);
        Gate::define('create-work', [WorkPolicy::class, 'create']);
        Gate::define('update-work', [WorkPolicy::class, 'update']);
        Gate::define('delete-work', [WorkPolicy::class, 'delete']);
        Gate::define('restore-work', [WorkPolicy::class, 'restore']);
        Gate::define('force-delete-work', [WorkPolicy::class, 'forceDelete']);
    }
}
