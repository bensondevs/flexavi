<?php

namespace App\Services\Mollie;

use App\Repositories\Subscription\{SubscriptionPaymentRepository, SubscriptionRepository};
use App\Services\Mollie\PaymentService as MolliePaymentService;
use App\Services\Subscription\PaymentService;
use Mollie\Api\Exceptions\ApiException;

class WebhookPaymentService
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
     * Mollie Payment Service Class Container
     *
     * @var MolliePaymentService
     */
    private \App\Services\Mollie\PaymentService $molliePaymentService;

    /**
     *  Payment Service Class Container
     *
     * @var PaymentService
     */
    private PaymentService $paymentService;


    /**
     * Service constructor method
     *
     * @param SubscriptionRepository $subscriptionRepository ,
     * @param SubscriptionPaymentRepository $subscriptionPaymentRepository ,
     * @param MolliePaymentService $molliePaymentService
     * @param PaymentService $paymentService
     * @return void
     */
    public function __construct(
        SubscriptionRepository        $subscriptionRepository,
        SubscriptionPaymentRepository $subscriptionPaymentRepository,
        MolliePaymentService          $molliePaymentService,
        PaymentService                $paymentService
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionPaymentRepository = $subscriptionPaymentRepository;
        $this->molliePaymentService = $molliePaymentService;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle webhook mollie
     *
     * @param mixed $payment
     * @return void
     * @throws ApiException
     */
    public function handle(mixed $payment): void
    {
        $subscriptionPayment = $this->subscriptionPaymentRepository->find($payment->metadata->paymentable_id);
        $subscription = $this->subscriptionRepository->find($subscriptionPayment->subscription_id);

        $this->subscriptionPaymentRepository->setModel($subscriptionPayment);
        $this->subscriptionRepository->setModel($subscriptionPayment->subscription);

        if ($payment->isPaid()) {
            $this->paymentService->handleIsPaid($subscription);
        }

        if ($payment->isFailed()) {
            $this->subscriptionPaymentRepository->failed();
        }

        if ($payment->isOpen()) {
            $this->subscriptionPaymentRepository->waiting();
        }

        if ($payment->isExpired()) {
            $this->subscriptionPaymentRepository->expired();
            $this->subscriptionPaymentRepository->waiting();
            app(PaymentService::class)->createMolliePayment($subscriptionPayment);
        }
    }
}
