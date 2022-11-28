<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Get all customer salutation types
     *
     * @return JsonResponse
     */
    public function allSalutationTypes(): JsonResponse
    {
        return response()->json(Customer::collectAllSalutationTypes());
    }

    /**
     * Get all acquisition types
     *
     * @return JsonResponse
     */
    public function allAcquisitionTypes(): JsonResponse
    {
        return response()->json(Customer::collectAllAcquisitionTypes());
    }
}
