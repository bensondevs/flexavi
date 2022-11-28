<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    /**
     * Get all invoice statuses enums
     *
     * @return JsonResponse
     */
    public function allStatuses(): JsonResponse
    {
        $statuses = Invoice::collectAllStatuses();
        return response()->json($statuses);
    }

    /**
     * Get selectable statuses enums
     *
     * @return JsonResponse
     */
    public function selectableStatuses(): JsonResponse
    {
        $selectableStatuses = Invoice::collectStatusOptions();
        return response()->json($selectableStatuses);
    }

    /**
     * Get all invoice payment methods enums
     *
     * @return JsonResponse
     */
    public function allPaymentMethods(): JsonResponse
    {
        $methods = Invoice::collectAllPaymentMethods();
        return response()->json($methods);
    }
}
