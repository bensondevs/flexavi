<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Jobs\Invoice\ChangeOverdueInvoiceStatus;

use App\Repositories\InvoiceRepository;

class CheckOverdueInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $overdueInvoices = $this->invoice->overdueInvoices();
        foreach ($overdueInvoices as $invoice) {
            $changeStatusJob = new ChangeOverdueInvoiceStatus($invoice);
            dispatch($changeStatusJob);
        }
    }
}
