<?php

namespace Tests\Unit\Services\Subscription\Notification\SubscriptionPaymentNotificationService;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionPaymentSuccessful;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\User\User;

/**
 * @see \App\Services\Subscription\Notification\SubscriptionPaymentNotificationService::paymentSuccess()
 *      To the tested service method.
 */
class PaymentSuccessTest extends SubscriptionPaymentNotificationServiceTest
{
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
        $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->withoutPaymentGateway()->create();
        $this->service()->paymentSuccess($subscriptionPayment);
        \Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof SubscriptionPaymentSuccessful;
        });
    }
}
