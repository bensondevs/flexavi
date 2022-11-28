<?php

namespace Tests\Unit\Models\SubscriptionPayment;

use App\Enums\SubscriptionPayment\SubscriptionPaymentStatus;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPaymentApiResponse;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPaymentTest extends \Tests\TestCase
{
    /**
     * Testing ensure payment has subscription relationship
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::subscription()
     *      To see model relationship
     */
    public function ensure_payment_has_subscription_relationship(): void
    {
        $subscription = Subscription::factory()->create();
        $payment = SubscriptionPayment::factory()->for($subscription)->create();

        $this->assertInstanceOf(Subscription::class, $payment->subscription);
        $this->assertInstanceOf(BelongsTo::class, $payment->subscription());
    }

    /**
     * Testing ensure `setStatus` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::setStatus()
     *      To see model method
     */
    public function ensure_set_status_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->create();
        $status = SubscriptionPaymentStatus::getRandomValue();
        $this->assertTrue($payment->setStatus($status));
        $this->assertDatabaseHas((new SubscriptionPayment())->getTable(), [
            'id' => $payment->id,
            'status' => $status
        ]);
    }


    /**
     * Testing ensure `isWaiting` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::isWaiting()
     *      To see model method
     */
    public function ensure_is_waiting_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->waiting()->create();
        $this->assertTrue($payment->isWaiting());
    }

    /**
     * Testing ensure `isSettled` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::isSettled()
     *      To see model method
     */
    public function ensure_is_settled_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->settled()->create();
        $this->assertTrue($payment->isSettled());
    }

    /**
     * Testing ensure `isExpired` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::isExpired()
     *      To see model method
     */
    public function ensure_is_expired_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->expired()->create();
        $this->assertTrue($payment->isExpired());
    }

    /**
     * Testing ensure `isFailed` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::isFailed()
     *      To see model method
     */
    public function ensure_is_failed_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->failed()->create();
        $this->assertTrue($payment->isFailed());
    }

    /**
     * Testing ensure `isRefunded` method is valid
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPayment::isRefunded()
     *      To see model method
     */
    public function ensure_is_refunded_method_valid(): void
    {
        $payment = SubscriptionPayment::factory()->refunded()->create();
        $this->assertTrue($payment->isRefunded());
    }
}
