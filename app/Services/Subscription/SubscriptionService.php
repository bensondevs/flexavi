<?php

namespace App\Services\Subscription;

use App\Models\Company\Company;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Laravel\Cashier\Exceptions\PlanNotFoundException;
use Throwable;

class SubscriptionService
{
    /**
     * Create subscription for company.
     *
     * @param Company $company
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return array
     * @throws PlanNotFoundException
     * @throws Throwable
     */
    public function renew(Company $company, SubscriptionPlanPeriod $subscriptionPlanPeriod): array
    {
        if ($company->onGenericTrial()) {
            $response = $company->newSubscription('main', $subscriptionPlanPeriod->name)->create();
            return $this->prepareResponse($response);
        }

        if ($company->subscribedToPlan($subscriptionPlanPeriod->name, 'main')) {
            abort(422, 'You are already subscribed to this plan');
        }

        if ($company->subscriptions_count === 0) {
            $response = $company->newSubscription('main', $subscriptionPlanPeriod->name)->create();
            return $this->prepareResponse($response);
        }

        $response = $company->subscription('main')->swapNextCycle($subscriptionPlanPeriod->name)->create();
        return $this->prepareResponse($response);
    }

    /**
     * Prepare response for subscription.
     *
     * @param mixed $response
     * @return array
     *
     */
    private function prepareResponse(mixed $response): array
    {
        if ($response instanceof Subscription) {
            return [
                'success' => true,
                'message' => 'Successfully to swap plan on next cycle',
            ];
        }

        if ($response) {
            $payment = $response->payment();

            return [
                'success' => true,
                'message' => 'Successfully to renew subscription.',
                'data' => [
                    'mobile_checkout_url' => $payment->getMobileAppCheckoutUrl(),
                    'checkout_url' => $payment->getCheckoutUrl(),
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to renew subscription.',
        ];
    }
}
