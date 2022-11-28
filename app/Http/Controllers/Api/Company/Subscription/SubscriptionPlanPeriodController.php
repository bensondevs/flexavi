<?php

namespace App\Http\Controllers\Api\Company\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\SubscriptionPlans\{FindSubscriptionPlanPeriodRequest as FindRequest,
    PopulateSubscriptionPlanPeriodsRequest as PopulatePeriodsRequest
};
use App\Http\Resources\Subscription\SubscriptionPlanPeriodResource;
use App\Repositories\Subscription\SubscriptionPlanPeriodRepository;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanPeriodTest
 *      To see the test
 */
class SubscriptionPlanPeriodController extends Controller
{
    /**
     * Subscription plan period container variable
     *
     * @var SubscriptionPlanPeriodRepository
     */
    private SubscriptionPlanPeriodRepository $subscriptionPlanPeriodRepository;

    /**
     * Controller constructor method
     *
     * @param SubscriptionPlanPeriodRepository $subscriptionPlanPeriodRepository
     */
    public function __construct(SubscriptionPlanPeriodRepository $subscriptionPlanPeriodRepository)
    {
        $this->subscriptionPlanPeriodRepository = $subscriptionPlanPeriodRepository;
    }

    /**
     * Populate subscription plan periods
     *
     * @param PopulatePeriodsRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanPeriodTest::test_populate_subscription_plan_periods()
     *      To see the test
     */
    public function subscriptionPlanPeriods(PopulatePeriodsRequest $request): JsonResponse
    {
        $periods = $this->subscriptionPlanPeriodRepository->all($request->options());
        $periods = SubscriptionPlanPeriodResource::collection($periods);
        return response()->json([
            'subscription_plan_periods' => $periods
        ]);
    }

    /**
     * Find subscription plan period
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionPlanPeriodTest::test_view_subscription_plan_period()
     *      To see the test
     */
    public function view(FindRequest $request): JsonResponse
    {
        $period = $request->getSubscriptionPlanPeriod();
        $period = new SubscriptionPlanPeriodResource($period);
        return response()->json([
            'subscription_plan_period' => $period
        ]);
    }
}
