<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cost;

class CostController extends Controller
{
    public function allCostableTypes()
    {
        $types = Cost::collectAllCostableTypes();

        return response()->json(['types' => $types]);
    }
}
