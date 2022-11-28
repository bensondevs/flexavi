<?php

namespace Tests\Unit\Services\Subscription\SubscriptionBackgroundProcessService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionThreeDaysLeftExpired;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Queue;

/**
 * @see \App\Services\Subscription\SubscriptionBackgroundProcessService::threeDaysBeforeExpiredReminder()
 *      To the tested service method.
 */
class ThreeDaysBeforeExpiredReminderTest extends SubscriptionBackgroundProcessServiceTest
{
    use WithFaker;

    /**
     * Test send reminder to user 3 days before subscription expired.
     *
     * @return void
     */
    public function test_send_reminder_to_owner_about_the_expiration_of_the_subscription_three_days_before_the_end_of_the_subscription(): void
    {
        Queue::fake();
        Mail::fake();
        $users = User::factory()->owner()->count(rand(1, 4))->create();
        $subscriptionsId = [];
        foreach ($users as $user) {
            $subscription = Subscription::factory()->for($user->owner->company)->create([
                'subscription_end' => now()->subDay(3)->format('Y-m-d'),
            ]);
            $subscriptionsId[] = $subscription->id;
        }

        Subscription::whereNotIn('id', $subscriptionsId)->delete();

        $this->service()->threeDaysBeforeExpiredReminder();

        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            foreach ($subscription->company->owners as $owner) {
                Queue::assertPushed(SendMail::class, function ($job) use ($owner) {
                    $user = $owner->user ? $owner->user : User::find($owner->user_id);
                    return $job->mailable instanceof SubscriptionThreeDaysLeftExpired && $job->destination === $user->email;
                });
            }
        }
    }
}
