<?php

namespace Tests\Unit\Services\Subscription\SubscriptionTrialService;

use App\Services\Subscription\SubscriptionTrialService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Services\Subscription\SubscriptionTrialService
 *      This is a test class for SubscriptionTrialService
 */
class SubscriptionTrialServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service instance container property.
     *
     * @var SubscriptionTrialService|null
     */
    private ?SubscriptionTrialService $subscriptionTrialService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            SubscriptionTrialService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return SubscriptionTrialService
     */
    protected function service(bool $force = false): SubscriptionTrialService
    {
        if ($this->subscriptionTrialService instanceof SubscriptionTrialService and !$force) {
            return $this->subscriptionTrialService;
        }

        return $this->subscriptionTrialService = app(SubscriptionTrialService::class);
    }
}
