<?php

namespace App\Http\Controllers\Api\Company\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\SubscriptionPlans\{FindSubscriptionPlanRequest as FindRequest,
    PopulateSubscriptionPlansRequest as PopulateRequest
};
use App\Http\Resources\{Subscription\SubscriptionPlanResource};
use App\Repositories\{Subscription\SubscriptionPlanRepository};
use Illuminate\Http\JsonResponse;


/**
 * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanTest
 *      To see the test
 */
class SubscriptionPlanController extends Controller
{
    /**
     * Subscription plan Repository Class Container
     *
     * @var SubscriptionPlanRepository
     */
    private SubscriptionPlanRepository $subscriptionPlanRepository;


    /**
     * Controller constructor method
     *
     * @param SubscriptionPlanRepository $subscriptionPlanRepository
     * @return void
     */
    public function __construct(
        SubscriptionPlanRepository $subscriptionPlanRepository
    )
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }

    /**
     * All subscription plans
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanTest::test_populate_subscription_plans()
     *    To see the test
     */
    public function subscriptionPlans(PopulateRequest $request): JsonResponse
    {
        $plans = $this->subscriptionPlanRepository->all($request->options());
        $plans = SubscriptionPlanResource::collection($plans);
        return response()->json([
            'subscription_plans' => $plans
        ]);
    }

    /**
     * All subscription plans
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanTest::test_view_subscription_plan()
     *      To see the test
     */
    public function view(FindRequest $request): JsonResponse
    {
        $subscriptionPlan = $request->getSubscriptionPlan();
        $subscriptionPlan = new SubscriptionPlanResource($subscriptionPlan);
        return response()->json([
            'subscription_plan' => $subscriptionPlan
        ]);
    }


}
