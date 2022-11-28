<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Car\CarRegisterTime;

class CarRegisterTimeController extends Controller
{
    /**
     * Get all car register time types enum
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        $types = CarRegisterTime::collectAllPassangerTypes();
        return response()->json($types);
    }
}
