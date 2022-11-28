<?php

namespace Tests\Unit\Services\Subscription\SubscriptionBackgroundProcessService;

use App\Services\Subscription\SubscriptionBackgroundProcessService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Services\Subscription\SubscriptionBackgroundProcessService
 *      This is a test class
 */
class SubscriptionBackgroundProcessServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service instance container property.
     *
     * @var SubscriptionBackgroundProcessService|null
     */
    private ?SubscriptionBackgroundProcessService $subscriptionTrialService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            SubscriptionBackgroundProcessService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return SubscriptionBackgroundProcessService
     */
    protected function service(bool $force = false): SubscriptionBackgroundProcessService
    {
        if ($this->subscriptionTrialService instanceof SubscriptionBackgroundProcessService and !$force) {
            return $this->subscriptionTrialService;
        }

        return $this->subscriptionTrialService = app(SubscriptionBackgroundProcessService::class);
    }
}
