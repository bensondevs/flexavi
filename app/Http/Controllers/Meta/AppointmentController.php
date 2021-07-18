<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function allCancellationVaults()
    {
        $vaults = Appointment::collectAllCancellationVaults();

        return response()->json(['cancellation_vaults' => $vaults]);
    }

    public function allStatuses()
    {
        $statuses = Appointment::collectAllStatuses();

        return response()->json(['statuses' => $statuses]);
    }

    public function allTypes()
    {
        $types = Appointment::collectAllTypes();

        return response()->json(['types' => $types]);
    }
}
