<?php

namespace App\Jobs\Invoice\Reminder;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class CustomerReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public int $timeout = 1200;

    /**
     * @var Mailable
     */
    public Mailable $mailable;

    /**
     * @var Invoice
     */
    public Invoice $invoice;

    /**
     * @var string
     */
    public string $destination;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mailable $mailable, Invoice $invoice, string $destination = '')
    {
        $this->mailable = $mailable;
        $this->destination = $destination;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->destination)->send($this->mailable);
    }
}
