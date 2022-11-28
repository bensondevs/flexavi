<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionNotificationService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionLastDayLeftExpired;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionNotificationService::lastDayReminder()
 *      To the tested service method.
 */
class LastDayReminderTest extends SubscriptionNotificationServiceTest
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
        $this->service()->lastDayReminder($subscription);
        \Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof SubscriptionLastDayLeftExpired;
        });
    }
}
