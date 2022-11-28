<?php

namespace App\Jobs\Company;

use App\Models\Company\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDestroyedCompanies implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $companies = Company::onlyTrashed()
            ->whereNotNull('will_be_permanently_deleted_at')
            ->where('will_be_permanently_deleted_at', '>=', now()->copy())
            ->get();
        foreach ($companies as $company) {
            $company->forceDelete();
        }
    }
}
