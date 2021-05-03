<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{
    protected $employee;

    public function __construct(EmployeeRepository $employee)
    {
    	$this->employee = $employee;
    }

    public function current()
    {
    	return response()->json([
    		'employee' => auth()->user()
    	]);
    }
}
