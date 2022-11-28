<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionNotificationService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionFreeTrial;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionNotificationService::trialStarted()
 *      To the tested service method.
 */
class TrialStartedTest extends SubscriptionNotificationServiceTest
{
    use WithFaker;

    /**
     * Ensure this method pushed a notification to the user.
     *
     * @test
     */
    public function ensure_this_method_pushed_job(): void
    {
        \Mail::fake();
        \Queue::fake();
        $user = User::factory()->owner()->create();
        $subscription = Subscription::factory()->for($user->owner->company)->create();
        $this->service()->trialStarted($subscription);
        \Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof SubscriptionFreeTrial;
        });
    }
}
