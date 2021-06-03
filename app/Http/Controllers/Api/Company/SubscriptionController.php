<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Subscriptions\PopulateCompanySubscripionsRequest

use App\Repositories\SubscriptionRepository;

use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    private $subscription;

    public function __construct(SubscriptionRepository $subscription)
    {
        $this->subscription = $subscription;
    }

    public function companySubscriptions()
    {
        $options = $request->options();
        $subscriptions = $this->subscription->all($options);
        $subscriptions = $this->subscription->paginate();
        $subscriptions->data = SubscriptionResource::collection($subscriptions);

        return response()->json(['subscriptions' => $subscriptions]);
    }
}
