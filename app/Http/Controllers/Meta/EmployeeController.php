<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;

class EmployeeController extends Controller
{
    public function allTypes()
    {
        $types = Employee::collectAllTypes();

        return response()->json($types);
    }

    public function allEmploymentStatuses()
    {
        $statuses = Employee::collectAllEmploymentStatus();

        return response()->json($statuses);
    }
}