<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\WorkService\WorkService;

class WorkServiceController extends Controller
{
    /**
     * Get possible workservice statuses
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = WorkService::collectAllStatuses();
        return response()->json($statuses);
    }
}
