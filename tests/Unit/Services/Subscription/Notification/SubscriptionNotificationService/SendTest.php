<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionNotificationService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionStarted;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionNotificationService::send()
 *      To the tested service method.
 */
class SendTest extends SubscriptionNotificationServiceTest
{
    use WithFaker;

    /**
     * Ensure the method is sending the mail to the user.
     *
     * @test
     * @return void
     */
    public function ensure_this_method_pushed_send_mail_job(): void
    {
        \Mail::fake();
        \Queue::fake();
        $user = User::factory()->owner()->create();
        $subscription = Subscription::factory()->for($user->owner->company)->create();
        $this->service()->send(SubscriptionStarted::class, $subscription);
        \Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof SubscriptionStarted;
        });
    }
}
