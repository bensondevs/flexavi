<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Laravel\Cashier\Exceptions\PlanNotFoundException;
use Laravel\Cashier\Plan\Plan;

class MolliePlanRepository
{
    /**
     * @throws PlanNotFoundException
     */
    public static function findOrFail(string $name): ?Plan
    {
        if (($result = self::find($name)) === null) {
            throw new PlanNotFoundException();
        }

        return $result;
    }

    public static function find(string $name): ?Plan
    {
        $subscriptionPlan = SubscriptionPlan::where('name', 'BASIC')->first();

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::where('subscription_plan_id', $subscriptionPlan->id)->where('name', $name)->first();

        if (is_null($subscriptionPlanPeriod)) {
            return null;
        }

        return $subscriptionPlanPeriod->buildCashierPlan();

    }
}
