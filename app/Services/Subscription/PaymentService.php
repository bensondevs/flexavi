<?php

namespace App\Services\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Repositories\Subscription\{SubscriptionPaymentRepository, SubscriptionRepository};
use App\Services\Mollie\PaymentService as MolliePaymentService;
use Mollie\Api\Exceptions\ApiException;

class PaymentService
{
    /**
     * Subscription Repository Class Container
     *
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscriptionRepository;

    /**
     * Subscription Payment Repository Class Container
     *
     * @var SubscriptionPaymentRepository
     */
    private SubscriptionPaymentRepository $subscriptionPaymentRepository;

    /**
     * Service constructor method
     *
     * @param SubscriptionRepository $subscriptionRepository
     * @param SubscriptionPaymentRepository $subscriptionPaymentRepository
     * @return void
     */
    public function __construct(
        SubscriptionRepository        $subscriptionRepository,
        SubscriptionPaymentRepository $subscriptionPaymentRepository
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionPaymentRepository = $subscriptionPaymentRepository;
    }

    /**
     * Create a new subscription payment
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return void
     * @throws ApiException
     */
    public function createMolliePayment(SubscriptionPayment $subscriptionPayment): void
    {
        $paymentService = (new MolliePaymentService)->create($subscriptionPayment);
        $this->subscriptionPaymentRepository->setModel($subscriptionPayment);
        $this->subscriptionPaymentRepository->save([
            'payment_gateway_checkout_url' => [
                'mobile' => $paymentService->getMobileAppCheckoutUrl(),
                'non_mobile' => $paymentService->getCheckoutUrl()
            ],
            'payment_gateway_payment_id' => $paymentService->id,
        ]);
    }

    /**
     * Handle payment is paid
     *
     * @param Subscription $subscription
     * @return void
     */
    public function handleIsPaid(Subscription $subscription): void
    {
        $this->subscriptionRepository->setModel($subscription);
        $this->subscriptionPaymentRepository->setModel($subscription->payment);
        $this->subscriptionPaymentRepository->settled();
        $activeSubscription = Subscription::where('company_id', $subscription->company_id)
            ->active()
            ->whereNot('id', $subscription->id)
            ->first();

        $activeSubscription ? $this->subscriptionRepository->inactive() : $this->subscriptionRepository->start();
    }
}
