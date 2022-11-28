<?php

namespace App\Http\Controllers\Api\Company\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Subscriptions\{FindSubscriptionRequest,
    PopulateCompanySubscriptionsRequest as PopulateRequest,
    RenewSubscriptionRequest as RenewRequest
};
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\Subscription\SubscriptionService;
use App\Traits\CompanyInputRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Cashier\Exceptions\InvalidMandateException;
use Laravel\Cashier\Exceptions\PlanNotFoundException;
use Throwable;

class SubscriptionController extends Controller
{
    use CompanyInputRequest;

    /**
     * Subscription Repository Class Container
     *
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscriptionRepository;

    /**
     * Subscription Service Class Container
     *
     * @var SubscriptionService
     */
    private SubscriptionService $subscriptionService;


    /**
     * Controller constructor method
     *
     * @param SubscriptionRepository $subscription
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(
        SubscriptionRepository $subscription,
        SubscriptionService    $subscriptionService
    )
    {
        $this->subscriptionRepository = $subscription;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Company subscriptions logs
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Subscription\SubscriptionTest::test_populate_subscriptions()
     *      To see how to test this method
     */
    public function companySubscriptions(PopulateRequest $request): JsonResponse
    {
        $subscriptions = $this->subscriptionRepository->all($request->options(), true);
        $subscriptions = SubscriptionResource::apiCollection($subscriptions);

        return response()->json(['subscriptions' => $subscriptions]);
    }

    /**
     * Renew subscription
     *
     * @param RenewRequest $request
     * @return JsonResponse
     * @throws InvalidMandateException
     * @throws PlanNotFoundException
     * @throws Throwable
     */
    public function renewSubscription(RenewRequest $request): JsonResponse
    {
        $company = $request->getCompany();
        $subscriptionPlanPeriod = $request->getSubscriptionPlanPeriod();
        $renewService = $this->subscriptionService->renew($company, $subscriptionPlanPeriod);
        return response()->json($renewService);
    }

    /**
     * View subscription
     *
     * @param FindSubscriptionRequest $request
     * @return JsonResponse
     */
    public function view(FindSubscriptionRequest $request): JsonResponse
    {
        $subscription = $request->getSubscription();
        $subscription = new SubscriptionResource($subscription);
        return response()->json(['subscription' => $subscription]);
    }
}
