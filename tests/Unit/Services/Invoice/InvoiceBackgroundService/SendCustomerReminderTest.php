<?php

namespace Tests\Unit\Services\Invoice\InvoiceBackgroundService;

use App\Jobs\SendMail;
use App\Mail\Invoice\InvoiceFirstReminder;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\User\User;
use Mail;
use Queue;

/**
 * @see \App\Services\Invoice\InvoiceBackgroundService::sendCustomerReminder()
 *     To see method under test
 */
class SendCustomerReminderTest extends InvoiceBackgroundServiceTest
{
    /**
     * Test it sends invoice to customer email
     * @test
     * @return void
     */
    public function it_send_reminder_to_customer(): void
    {
        Mail::fake();
        Queue::fake();
        $user = User::factory()->owner()->create();
        $owner = $user->owner;
        $company = $owner->company;

        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()->for($company)->for($customer)->create();
        $mailable = new InvoiceFirstReminder($invoice);
        $this->service()->sendCustomerReminder($mailable, $invoice);
        Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof InvoiceFirstReminder && $job->destination === $customer->email;
        });

    }
}
