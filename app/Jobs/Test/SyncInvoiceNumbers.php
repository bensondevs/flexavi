<?php

namespace App\Jobs\Test;

use App\Jobs\Invoice\GenerateInvoiceNumber;
use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncInvoiceNumbers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

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
    public function handle()
    {
        $sentInvoices = Invoice::get();
        foreach ($sentInvoices as $sentInvoice) {
            $geneateNumber = new GenerateInvoiceNumber($sentInvoice);
            $geneateNumber->delay(1);
            dispatch($geneateNumber);
        }
    }
}
