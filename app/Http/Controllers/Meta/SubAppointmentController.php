<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubAppointment;

class SubAppointmentController extends Controller
{
    public function allCancellationVaults()
    {
        $vaults = SubAppointment::collectAllCancellationVaults();

        return response()->json($vaults);
    }

    public function allStatuses()
    {
        $statuses = SubAppointment::collectAllStatuses();

        return response()->json($statuses);
    }
}
