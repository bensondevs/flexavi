<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\Invoice\CheckOverdueInvoices;

class RunCheckOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:checkoverdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Job to check overdue invoice and update them to overdue status according to its phase';

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
        $checkingJob = new CheckOverdueInvoices;
        dispatch($checkingJob);

        return $this->info('Checking Overdue Invoices in progress...');
    }
}
