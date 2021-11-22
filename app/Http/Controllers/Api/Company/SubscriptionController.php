<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Subscriptions\{
    PopulateCompanySubscripionsRequest as PopulateRequest
};
use App\Repositories\SubscriptionRepository;
use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    /**
     * Subscription Repository Class Container
     * 
     * @var \App\Repository\SubscriptionRepository
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
     * @param PopulateRequest
     * @return Illuminate\Support\Facades\Response
     */
    public function companySubscriptions()
    {
        //
    }

    /**
     * Subscribe to a plan
     * 
     * @param SubscribeRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function subscribe()
    {
        //
    }
}
