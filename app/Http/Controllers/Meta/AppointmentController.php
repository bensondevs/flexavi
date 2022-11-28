<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Get all appointment cancellation vaults enum
     *
     * @return JsonResponse
     */
    public function allCancellationVaults(): JsonResponse
    {
        $vaults = Appointment::collectAllCancellationVaults();
        return response()->json($vaults);
    }

    /**
     * Get all appointment statuses enum
     *
     * @return JsonResponse
     */
    public function allStatuses(): JsonResponse
    {
        $statuses = Appointment::collectAllStatuses();
        return response()->json($statuses);
    }

    /**
     * Get all appointment types enum
     *
     * @return JsonResponse
     */
    public function allTypes(): JsonResponse
    {
        $types = Appointment::collectAllTypes();
        return response()->json($types);
    }
}
