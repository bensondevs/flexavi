<?php

namespace Tests\Unit\Mail\Invoice;

use App\Jobs\SendMail;
use App\Mail\Invoice\SendInvoice;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Queue;
use Tests\TestCase;

/**
 * @see \App\Mail\Invoice\SendInvoice
 *      To the mailable class
 */
class SendInvoiceTest extends TestCase
{
    use WithFaker;

    /**
     * Ensure mailable class running correctly
     *
     * @test
     * @return void
     */
    public function ensure_mailable_class_running_correctly(): void
    {
        Queue::fake();
        Mail::fake();
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create();
        $instance = resolve(SendMail::class, [
            'mailable' => new SendInvoice($invoice),
            'destination' => $customer->email
        ]);
        app()->call([$instance, 'handle']);
        Mail::assertSent(SendInvoice::class);
    }
}
