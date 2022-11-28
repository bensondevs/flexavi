<?php

namespace Tests\Unit\Services\Invoice\InvoiceBackgroundService;

use App\Jobs\SendMail;
use App\Mail\Invoice\SendInvoice;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\User\User;
use Mail;
use Queue;

/**
 * @see \App\Services\Invoice\InvoiceBackgroundService::send()
 *     To see method under test
 */
class SendTest extends InvoiceBackgroundServiceTest
{
    /**
     * Test it sends invoice to customer email
     *
     * @return void
     */
    public function test_it_sends_invoice_to_customer_email(): void
    {
        Mail::fake();
        Queue::fake();
        $user = User::factory()->owner()->create();
        $owner = $user->owner;
        $company = $owner->company;

        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()->for($company)->for($customer)->create();
        $this->service()->send($invoice);

        Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof SendInvoice && $job->destination === $customer->email;
        });
    }
}
