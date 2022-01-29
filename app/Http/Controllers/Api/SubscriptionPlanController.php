<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
    /**
     * Populate subscription plans
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function plans()
    {
        return response()->json([
            'subscription_plans' => SubscriptionPlan::all()
        ]);
    }
}
