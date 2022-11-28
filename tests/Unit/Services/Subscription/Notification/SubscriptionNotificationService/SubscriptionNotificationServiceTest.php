<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionNotificationService;

use App\Services\Subscription\Notification\SubscriptionNotificationService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionNotificationService
 *      This is a test class
 */
class SubscriptionNotificationServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service instance container property.
     *
     * @var SubscriptionNotificationService|null
     */
    private ?SubscriptionNotificationService $subscriptionTrialService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            SubscriptionNotificationService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return SubscriptionNotificationService
     */
    protected function service(bool $force = false): SubscriptionNotificationService
    {
        if ($this->subscriptionTrialService instanceof SubscriptionNotificationService and !$force) {
            return $this->subscriptionTrialService;
        }

        return $this->subscriptionTrialService = app(SubscriptionNotificationService::class);
    }
}
