<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Work\Work;

class WorkController extends Controller
{
    /**
     * Get all work statuses
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = Work::collectAllStatuses();
        return response()->json($statuses);
    }
}
