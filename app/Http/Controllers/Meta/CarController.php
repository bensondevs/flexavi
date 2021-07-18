<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;

class CarController extends Controller
{
    public function allStatuses()
    {
        $statuses = Car::collectAllStatuses();

        return response()->json(['statuses' => $statuses]);
    }
}
