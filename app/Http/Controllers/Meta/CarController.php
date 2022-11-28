<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Car\Car;

class CarController extends Controller
{
    /**
     * Get all car statuses enum
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = Car::collectAllStatuses();
        return response()->json($statuses);
    }
}
