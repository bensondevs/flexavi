<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{
    /**
     * Employee Repository Class Container
     * 
     * @var \App\Repositories\EmployeeRepository
     */
    private $employee;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\EmployeeRepository  $employee
     * @return void
     */
    public function __construct(EmployeeRepository $employee)
    {
    	$this->employee = $employee;
    }

    /**
     * Get current customer information
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function current()
    {
        $user = $request->user();
        $employee = new EmployeeResource($user->employee);
    	return response()->json(['employee' => $employee]);
    }
}
