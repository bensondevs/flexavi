<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Cost\Cost;

class CostController extends Controller
{
    /**
     * Get all costable types enums
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allCostableTypes()
    {
        $types = Cost::collectAllCostableTypes();
        return response()->json($types);
    }
}
