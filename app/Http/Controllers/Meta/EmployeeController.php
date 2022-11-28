<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Get all employee types enum
     *
     * @return JsonResponse
     */
    public function allTypes(): JsonResponse
    {
        $types = Employee::collectAllTypes();
        return response()->json($types);
    }

    /**
     * Get all employment statuses enums
     *
     * @return JsonResponse
     */
    public function allEmploymentStatuses(): JsonResponse
    {
        $statuses = Employee::collectAllEmploymentStatus();
        return response()->json($statuses);
    }
}
