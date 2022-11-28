<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->hideSensitiveRequestDetails();
        // Telescope::night();
        // Telescope::filter(function (IncomingEntry $entry) {
        //     if (app()->isLocal()) {
        //         return;
        //     }

        //     return $entry->isReportableException() ||
        //         $entry->isFailedRequest() ||
        //         $entry->isFailedJob() ||
        //         $entry->isScheduledTask() ||
        //         $entry->hasMonitoredTag();
        // });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if (app()->isLocal()) {
            return;
        }
        Telescope::hideRequestParameters(['_token']);
        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user = null) {
            if (app()->isLocal()) {
                return true;
            }
            return in_array($user->email, ['owner1@flexavi.nl']);
        });
    }
}
