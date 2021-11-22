<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Get all appointment cancellation vaults enum
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allCancellationVaults()
    {
        $vaults = Appointment::collectAllCancellationVaults();
        return response()->json($vaults);
    }

    /**
     * Get all appointment statuses enum
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = Appointment::collectAllStatuses();
        return response()->json($statuses);
    }

    /**
     * Get all appointment types enum
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        $types = Appointment::collectAllTypes();
        return response()->json($types);
    }
}
