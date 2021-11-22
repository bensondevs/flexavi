<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Get all employee types enum
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        $types = Employee::collectAllTypes();
        return response()->json($types);
    }

    /**
     * Get all employment statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allEmploymentStatuses()
    {
        $statuses = Employee::collectAllEmploymentStatus();
        return response()->json($statuses);
    }
}