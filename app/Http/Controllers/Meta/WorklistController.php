<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Worklist\Worklist;

class WorklistController extends Controller
{

    /**
     * Get possible worklist statuses
     * @return Illuminate\Support\Facades\Response
     */
    public function allWorklistStatuses()
    {
        return response()->json(Worklist::collectAllStatusesWorklist());
    }

    /**
     * Get possible worklist sorting route statuses
     * @return Illuminate\Support\Facades\Response
     */
    public function allWorklistSortingRouteStatuses()
    {
        return response()->json(Worklist::collectAllStatusesRouteSortingWorlist());
    }
}
