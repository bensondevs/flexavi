<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Work;

class WorkController extends Controller
{
    public function allStatuses()
    {
        return response()->json(Work::collectAllStatuses());
    }
}
