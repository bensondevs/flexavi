<?php

namespace App\Services\Mollie;

use App\Helpers\MollieHelper;
use App\Models\Subscription\SubscriptionPayment;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\BaseCollection;
use Mollie\Api\Resources\BaseResource;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Resources\PaymentCollection;
use Mollie\Laravel\Facades\Mollie;

class PaymentService
{
    /**
     * Creates a payment in Mollie.
     *
     * @param SubscriptionPayment $subscriptionPayment
     *
     * @return BaseResource|Payment
     * @throws ApiException
     */
    public function create(SubscriptionPayment $subscriptionPayment): BaseResource|Payment
    {
        return Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => mollieFormatAmount($subscriptionPayment->amount)
            ],
            'method' => $subscriptionPayment->payment_gateway_method,
            'description' => 'Payment For Subscription #' . $subscriptionPayment->subscription_id,
            'redirectUrl' => (new MollieHelper())->redirectUrl(),
            'webhookUrl' => (new MollieHelper())->paymentWebhookUrl(),
            'metadata' => [
                'paymentable_type' => get_class($subscriptionPayment),
                'paymentable_id' => $subscriptionPayment->id
            ]
        ]);
    }

    /**
     * Retrieve a single payment from Mollie.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     *
     * @param SubscriptionPayment $subscriptionPayment
     *
     * @return BaseResource|Payment
     * @throws ApiException
     */
    public function find(SubscriptionPayment $subscriptionPayment): BaseResource|Payment
    {
        return Mollie::api()->payments()->get($subscriptionPayment->payment_gateway_payment_id);
    }

    /**
     * Retrieves a collection of Payments from Mollie.
     *
     * @return BaseCollection|PaymentCollection
     * @throws ApiException
     */
    public function get(): BaseCollection|PaymentCollection
    {
        return Mollie::api()->payments()->page(null, 10);
    }

    /**
     * Update the given Payment.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @param array $data
     *
     * @return BaseResource|Payment
     * @throws ApiException
     */
    public function update(SubscriptionPayment $subscriptionPayment, array $data): BaseResource|Payment
    {
        return Mollie::api()->payments()->update($subscriptionPayment->payment_gateway_payment_id, [
            'method' => $data['payment_method'] ?? null
        ]);
    }

    /**
     * Cancel the given Payment. This is just an alias of the 'delete' method.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     * Returns with HTTP status No Content (204) if successful.
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return BaseResource|Payment
     * @throws ApiException
     */
    public function cancel(SubscriptionPayment $subscriptionPayment): BaseResource|Payment
    {
        return Mollie::api()->payments()->cancel($subscriptionPayment->payment_gateway_payment_id);
    }
}
