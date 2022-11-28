<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\WorkContract\WorkContract;
use Illuminate\Http\JsonResponse;

class WorkContractController extends Controller
{

    /**
     * Collect all work contract statuses
     *
     * @return JsonResponse
     */
    public function allStatuses(): JsonResponse
    {
        $allStatuses = WorkContract::collectAllStatuses();
        return response()->json($allStatuses);
    }
}
