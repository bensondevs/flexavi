<?php

namespace Tests\Unit\Mail\Subscription;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionPaymentFailed;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Mail\Subscription\SubscriptionPaymentFailed
 *      To the mailable class
 */
class SubscriptionPaymentFailedTest extends TestCase
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
            'mailable' => new SubscriptionPaymentFailed($subscription, $user->owner),
            'destination' => $this->faker->safeEmail
        ]);
        app()->call([$instance, 'handle']);
        \Mail::assertSent(SubscriptionPaymentFailed::class);
    }
}
