<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Http\Requests\Subscriptions\{
    PopulateCompanySubscripionsRequest as PopulateRequest,
    FindSubscriptionRequest as FindRequest
};
use App\Repositories\SubscriptionRepository;
use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    /**
     * Subscription Repository Class Container
     * 
     * @var \App\Repositories\SubscriptionRepository
     */
    private $subscription;

    /**
     * Controller constructor method
     * 
     * @param SubscriptionRepository  $subscription
     * @return void
     */
    public function __construct(SubscriptionRepository $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Company subscriptions logs
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companySubscriptions(PopulateRequest $request)
    {
        $company = $request->getCompany();
        $subscriptions = Subscription::forCompany($company)->get();
        $subscriptions = SubscriptionResource::apiCollection($subscriptions);

        return response()->json(['subscriptions' => $subscriptions]);
    }

    /**
     * Company active subscription
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function activeSubscription(PopulateRequest $request)
    {
        $company = $request->getCompany();
        $subscription = Subscription::forCompany($company)
            ->active()
            ->first();

        if (! $subscription) return response()->json([]);

        $subscription = new SubscriptionResource($subscription);
        return response()->json(['subscription' => $subscription]);
    }

    /**
     * Company active-able subscriptions
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function activableSubscriptions(PopulateRequest $request)
    {
        $company = $request->getCompany();
        $subscriptions = Subscription::forCompany($company)
            ->activable()
            ->get();
        $subscriptions = SubscriptionResource::apiCollection($subscriptions);

        return response()->json(['subscriptions' => $subscriptions]);
    }

    /**
     * View subscription
     * 
     * @param  FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $subscription = $request->getSubscription();
        $subscription = new SubscriptionResource($subscription);
        
        return response()->json([
            'subscription' => $subscription
        ]);
    }

    /**
     * Purchase subscription
     * 
     * @param  PurchaseRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function purchase(PurchaseRequest $request)
    {
        $input = $request->validated();
        $this->subscription->purchase($input);

        return apiResponse($this->subscription);
    }

    /**
     * Renewew subscription
     * 
     * @param  RenewRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function renew(RenewRequest $request)
    {
        $subscription = $request->getSubscription();
        
        $this->subscription->setModel($subscription);
        $this->subscription->renew();

        return apiResponse($this->subscription);
    }

    /**
     * Activate company subscription
     * 
     * @param  ActivateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function activate(ActivateRequest $request)
    {
        $subscription = $request->getSubscription();

        $this->subscription->setModel($subscription);
        $this->subscription->activate();

        return apiResponse($this->subscription);
    }

    /**
     * Terminate company subscription
     * 
     * @param  TerminateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function terminate(TerminateRequest $request)
    {
        $subscription = $request->getSubscription();

        $this->subscription->setModel($subscription);
        $this->subscription->terminate();

        return apiResponse($this->subscription);
    }
}
