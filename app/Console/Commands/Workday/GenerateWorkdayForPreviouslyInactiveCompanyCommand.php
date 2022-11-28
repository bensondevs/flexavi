<?php

namespace App\Console\Commands\Workday;

use App\Jobs\Workday\GenerateWorkdayForPreviouslyInactiveCompanyJob;
use App\Models\Company\Company;
use Illuminate\Console\Command;

class GenerateWorkdayForPreviouslyInactiveCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workday:generate-workdays-for-previously-inactive-company {companyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate workdays for previously inactive company';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $company = Company::find($this->argument('companyId'));
        dispatch(new GenerateWorkdayForPreviouslyInactiveCompanyJob($company));
        $this->info("Successfully to generate workdays for previously inactive company in queue.");
    }
}
