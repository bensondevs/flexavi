<?php

namespace App\Services\Subscription;

use App\Models\Subscription\Subscription;
use App\Repositories\Subscription\SubscriptionPaymentRepository;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\Subscription\Notification\SubscriptionNotificationService;

class SubscriptionBackgroundProcessService
{
    /**
     * Subscription repository container
     *
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscriptionRepository;

    /**
     * Subscription notification service container
     *
     * @var SubscriptionNotificationService
     */
    private SubscriptionNotificationService $subscriptionNotificationService;

    /**
     * Service constructor method
     *
     * @param SubscriptionRepository $subscriptionRepository
     * @param SubscriptionNotificationService $subscriptionNotificationService
     */
    public function __construct(
        SubscriptionRepository          $subscriptionRepository,
        SubscriptionNotificationService $subscriptionNotificationService,
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionNotificationService = $subscriptionNotificationService;
    }

    /**
     * Check subscriptions status
     *
     * @return void
     */
    public function checkSubscriptionsStatus(): void
    {
        $subscriptions = $this->subscriptionRepository->activeSubscriptionsWhosePeriodHasExpired();
        foreach ($subscriptions as $subscription) {
            $subscription->setExpired();

            /**
             * if the subscription is a trial then there will be no renewal of the subscription
             */
            if ($subscription->isTrial()) {
                continue;
            }

            /**
             * if the company has another subscription that has been paid for and has not been activated,
             * it will be activated on that subscription
             */
            $inactiveSubscriptions = Subscription::activable();

            if ($inactiveSubscriptions->exists()) {
                $inactiveSubscription = $inactiveSubscriptions->oldest()->first();
                $inactiveSubscription->start();
                $inactiveSubscription->setActive();
                continue;
            }

            /**
             * Create subscription renewal
             */
            $renewal = $subscription->createRenewal();
            app(SubscriptionPaymentRepository::class)->createPayment($renewal);
        }
    }

    /**
     * Send reminder to owner about subscription expiration
     *
     * @return void
     */
    public function threeDaysBeforeExpiredReminder(): void
    {
        $subscriptions = $this->subscriptionRepository->activeSubscriptionsWillExpireInThreeDays();

        foreach ($subscriptions as $subscription) {
            $this->subscriptionNotificationService->threeDaysReminder($subscription);
        }
    }

    /**
     * Send reminder to owner about subscription expiration on last day
     *
     * @return void
     */
    public function lastDayBeforeExpiredReminder(): void
    {
        $subscriptions = $this->subscriptionRepository->activeSubscriptionsWhoseNowIsLastDayOfPeriod();

        foreach ($subscriptions as $subscription) {
            $this->subscriptionNotificationService->lastDayReminder($subscription);
        }
    }
}
