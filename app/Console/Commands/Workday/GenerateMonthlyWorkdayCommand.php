<?php

namespace App\Console\Commands\Workday;

use App\Jobs\Workday\GenerateMonthlyWorkdayJob;
use Illuminate\Console\Command;

class GenerateMonthlyWorkdayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workday:generate-monthly-workdays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly workdays for company';

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
        dispatch(new GenerateMonthlyWorkdayJob);
        $this->info("Successfully to generate monthly workdays for company in queue.");
    }
}
