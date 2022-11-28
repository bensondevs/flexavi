<?php

namespace App\Http\Controllers\Api\Company\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\SubscriptionTrials\{CheckAvailabilityRequest,
    StartTrialSubscriptionRequest as StartRequest
};
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\Subscription\SubscriptionTrialService;
use Illuminate\Http\JsonResponse;

class SubscriptionTrialController extends Controller
{
    /**
     * Subscription trial service
     *
     * @var SubscriptionTrialService
     */
    private SubscriptionTrialService $subscriptionTrialService;

    /**
     * Subscription repository
     *
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscriptionRepository;

    /**
     * Controller constructor method
     *
     * @param SubscriptionTrialService $subscriptionTrialService
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(
        SubscriptionTrialService $subscriptionTrialService,
        SubscriptionRepository   $subscriptionRepository
    )
    {
        $this->subscriptionTrialService = $subscriptionTrialService;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Check availability of trial subscription.
     *
     * @param CheckAvailabilityRequest $request
     * @return JsonResponse
     */
    public function status(CheckAvailabilityRequest $request): JsonResponse
    {
        $company = $request->getCompany();
        $subscription = $this->subscriptionRepository->companyTrialSubscription($company);
        return response()->json([
            'availability' => !$subscription,
            'message' => $subscription ?
                'You already have a trial subscription.' :
                'You can start a trial subscription.'
        ]);
    }

    /**
     * Start trial subscription.
     *
     * @param StartRequest $request
     * @return JsonResponse
     */
    public function startTrial(StartRequest $request): JsonResponse
    {
        $service = $this->subscriptionTrialService->start($request->getCompany());
        return apiResponse($service);
    }
}
