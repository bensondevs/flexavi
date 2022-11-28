<?php

namespace App\Jobs\Invoice;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncInvoicesStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        foreach (Invoice::unpaid()->hasBeenOverdue()->get() as $invoice) {
            $invoice->setOverdue();
        }

        foreach (Invoice::unpaid()->hasBeenFirstReminderOverdue()->get() as $invoice) {
            $invoice->setFirstReminderOverdue();
        }

        foreach (Invoice::unpaid()->hasBeenSecondReminderOverdue()->get() as $invoice) {
            $invoice->setSecondReminderOverdue();
        }

        foreach (Invoice::unpaid()->hasBeenThirdReminderOverdue()->get() as $invoice) {
            $invoice->setThirdReminderOverdue();
        }

    }
}
