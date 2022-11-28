<?php

namespace Tests\Unit\Mail\Subscription;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionThreeDaysLeftExpired;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Mail\Subscription\SubscriptionThreeDaysLeftExpired
 *      To the mailable class
 */
class SubscriptionThreeDaysLeftExpiredTest extends TestCase
{
    use WithFaker;

    /**
     * Ensure mailable class running correctly
     *
     * @test
     * @return void
     */
    public function ensure_mailable_class_running_correctly(): void
    {
        \Queue::fake();
        \Mail::fake();
        $user = User::factory()->owner()->create();
        $subscription = Subscription::factory()->for($user->owner->company)->create();
        $instance = resolve(SendMail::class, [
            'mailable' => new SubscriptionThreeDaysLeftExpired($subscription, $user->owner),
            'destination' => $this->faker->safeEmail
        ]);
        app()->call([$instance, 'handle']);
        \Mail::assertSent(SubscriptionThreeDaysLeftExpired::class);
    }
}
