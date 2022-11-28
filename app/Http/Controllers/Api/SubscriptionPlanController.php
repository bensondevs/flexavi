<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\SubscriptionPlans\PopulateRequest;
use App\Http\Resources\Subscription\SubscriptionPlanResource;
use App\Repositories\Subscription\SubscriptionPlanRepository;

class SubscriptionPlanController extends Controller
{
    /**
     * Repository Container
     *
     * @var \App\Repositories\Subscription\SubscriptionPlanRepository
     */
    private $subscriptionPlan;


    /**
     * Create New Controller Instance
     *
     * @return void
     */
    public function __construct(
        SubscriptionPlanRepository $subscriptionPlanRepository
    ) {
        $this->subscriptionPlan = $subscriptionPlanRepository;
    }

    /**
     * Populate subscription plans
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function plans(PopulateRequest $request)
    {
        return response()->json([
            'subscription_plans' => SubscriptionPlanResource::collection(
                $this->subscriptionPlan->all($request->options(), false)
            )
        ]);
    }
}
