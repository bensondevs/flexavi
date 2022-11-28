<?php

namespace Tests\Unit\Services\Subscription\SubscriptionBackgroundProcessService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionLastDayLeftExpired;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Queue;

/**
 * @see \App\Services\Subscription\SubscriptionBackgroundProcessService::lastDayBeforeExpiredReminder()
 *      To the tested service method.
 */
class LastDayBeforeExpiredReminderTest extends SubscriptionBackgroundProcessServiceTest
{
    use WithFaker;

    /**
     * Test send reminder to user 1 day before subscription expired.
     *
     * @return void
     */
    public function test_send_reminder_to_owner_about_the_expiration_of_the_subscription_on_last_day(): void
    {
        Queue::fake();
        Mail::fake();
        $users = User::factory()->owner()->count(rand(1, 4))->create();
        $subscriptionsId = [];
        foreach ($users as $user) {
            $subscription = Subscription::factory()->for($user->owner->company)->create([
                'subscription_end' => now()->format('Y-m-d'),
            ]);
            $subscriptionsId[] = $subscription->id;
        }

        Subscription::whereNotIn('id', $subscriptionsId)->delete();

        $this->service()->lastDayBeforeExpiredReminder();

        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            foreach ($subscription->company->owners as $owner) {
                Queue::assertPushed(SendMail::class, function ($job) use ($owner) {
                    $user = $owner->user ? $owner->user : User::find($owner->user_id);
                    return $job->mailable instanceof SubscriptionLastDayLeftExpired && $job->destination === $user->email;
                });
            }
        }
    }
}
