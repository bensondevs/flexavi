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
 * @see \App\Services\Invoice\InvoiceBackgroundService::sendOwnersReminder()
 *     To see method under test
 */
class SendOwnersReminderTest extends InvoiceBackgroundServiceTest
{
    /**
     * Test it sends invoice to customer email
     * @test
     * @return void
     */
    public function it_send_reminder_to_owners(): void
    {
        Mail::fake();
        Queue::fake();
        $user = User::factory()->owner()->create();
        $owner = $user->owner;
        $company = $owner->company;

        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()->for($company)->for($customer)->create();
        foreach ($company->owners as $owner) {
            $mailable = new InvoiceFirstReminder($invoice);
            $this->service()->sendOwnersReminder($mailable, $invoice);
            Queue::assertPushed(SendMail::class, function ($job) use ($owner) {
                return $job->mailable instanceof InvoiceFirstReminder && $job->destination === $owner->user->email;
            });
        }

    }
}
