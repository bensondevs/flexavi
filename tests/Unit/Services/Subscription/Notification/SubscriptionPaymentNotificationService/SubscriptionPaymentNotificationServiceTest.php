<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionPaymentNotificationService;

use App\Services\Subscription\Notification\SubscriptionPaymentNotificationService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionPaymentNotificationService
 *      This is a test class
 */
class SubscriptionPaymentNotificationServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service instance container property.
     *
     * @var SubscriptionPaymentNotificationService|null
     */
    private ?SubscriptionPaymentNotificationService $subscriptionTrialService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            SubscriptionPaymentNotificationService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return SubscriptionPaymentNotificationService
     */
    protected function service(bool $force = false): SubscriptionPaymentNotificationService
    {
        if ($this->subscriptionTrialService instanceof SubscriptionPaymentNotificationService and !$force) {
            return $this->subscriptionTrialService;
        }

        return $this->subscriptionTrialService = app(SubscriptionPaymentNotificationService::class);
    }
}
