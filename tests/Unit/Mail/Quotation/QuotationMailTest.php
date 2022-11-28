<?php

namespace Tests\Unit\Mail\Quotation;

use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Queue;
use Tests\TestCase;

/**
 * @see \App\Mail\Quotation\QuotationMail
 *      To the mailable class
 */
class QuotationMailTest extends TestCase
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
        $quotation = Quotation::factory()->for($customer)->create();
        $instance = resolve(SendMail::class, [
            'mailable' => new QuotationMail($quotation),
            'destination' => $customer->email
        ]);
        app()->call([$instance, 'handle']);
        Mail::assertSent(QuotationMail::class);
    }
}
