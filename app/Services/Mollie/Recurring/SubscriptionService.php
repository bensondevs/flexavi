<?php

namespace App\Services\Mollie\Recurring;

use App\Models\{Company\Company, Subscription\Subscription};
use Illuminate\Support\Facades\Http;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\{BaseCollection, PaymentCollection, SubscriptionCollection};
use Mollie\Laravel\Facades\Mollie;

class SubscriptionService
{
    /**
     * Create subscription
     *
     * @param Company $company
     * @param Subscription $subscription
     * @return \Mollie\Api\Resources\Subscription
     * @throws ApiException
     */
    public function create(Company $company, Subscription $subscription): \Mollie\Api\Resources\Subscription
    {
        return Mollie::api()->customers()->get($company->mollie_customer_id)->createSubscription([
            'amount' => [
                'currency' => 'EUR',
                'value' => mollieFormatAmount($subscription->planPeriod->price),
            ],
            'interval' => '1 days',
            'description' => $subscription->id,
            'webhookUrl' => env('NGROK_HTTP_URL') . '/public/api/dashboard/companies/subscriptions/webhook',
            'metadata' => [
                'paymentable_type' => get_class($subscription->payment),
                'paymentable_id' => $subscription->payment->id,
                'plan_name' => $subscription->subscriptionPlanPeriod->subscriptionPlan->name,
                'plan_description' => $subscription->subscriptionPlanPeriod->subscriptionPlan->description,
                'period_name' => $subscription->subscriptionPlanPeriod->name,
                'period_description' => $subscription->subscriptionPlanPeriod->description,
                'duration_days' => $subscription->subscriptionPlanPeriod->duration_days,
                'formatted_duration_days' => $subscription->subscriptionPlanPeriod->duration_days . ' days',
            ]
        ]);
    }

    /**
     * List all subscriptions
     *
     * @return SubscriptionCollection
     * @throws ApiException
     */
    public function all(): SubscriptionCollection
    {
        return Mollie::api()->subscriptions()->page(null, 10, []);
    }

    /**
     * @param Company $company
     * @param Subscription $subscription
     * @return \Mollie\Api\Resources\Subscription
     * @throws ApiException
     */
    public function find(Company $company, Subscription $subscription): \Mollie\Api\Resources\Subscription
    {
        return Mollie::api()->subscriptions()->getForId($company->mollie_customer_id, $subscription->mollie_subscription_id);
    }

    /**
     * @param Company $company
     *
     * @return SubscriptionCollection
     * @throws ApiException
     */
    public function ofCompany(Company $company): SubscriptionCollection
    {
        return Mollie::api()->subscriptions()->listForId($company->mollie_customer_id);
    }

    /**
     * List all payments
     *
     * @param Company $company
     * @param Subscription $subscription
     * @return BaseCollection|PaymentCollection
     */
    public function payments(Company $company, Subscription $subscription): BaseCollection|PaymentCollection
    {
        return json_decode(Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MOLLIE_KEY')
        ])->get("https://api.mollie.com/v2/customers/$company->mollie_customer_id/subscriptions/$subscription->mollie_subscription_id/payments")->body());
    }
}
