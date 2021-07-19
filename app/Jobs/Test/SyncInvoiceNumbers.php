<?php

namespace App\Jobs\Test;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invoice;
use App\Enums\Invoice\InvoiceStatus;

use App\Jobs\Invoice\GenerateInvoiceNumber;

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
        $sentInvoices = Invoice::where('status', '>=', InvoiceStatus::Sent)->get();
        foreach ($sentInvoices as $sentInvoice) {
            $geneateNumber = new GenerateInvoiceNumber($sentInvoice);
            $geneateNumber->delay(1);
            dispatch($geneateNumber);
        }
    }
}
