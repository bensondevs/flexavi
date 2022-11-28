<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Appointment\SubAppointment;

class SubAppointmentController extends Controller
{
    /**
     * Get all sub appointment cancellation vaults
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allCancellationVaults()
    {
        $vaults = SubAppointment::collectAllCancellationVaults();
        return response()->json($vaults);
    }

    /**
     * Get all sub appointment statuses enums
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = SubAppointment::collectAllStatuses();
        return response()->json($statuses);
    }
}
