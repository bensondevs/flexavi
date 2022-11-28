<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\EmployeeResource;
use App\Repositories\Employee\EmployeeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Employee Repository Class Container
     *
     * @var EmployeeRepository
     */
    private EmployeeRepository $employeeRepository;

    /**
     * Controller constructor method
     *
     * @param EmployeeRepository $employeeRepository
     * @return void
     */
    public function __construct(EmployeeRepository $employeeRepository)
    {
    	$this->employeeRepository = $employeeRepository;
    }

    /**
     * Get current customer information
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request): JsonResponse
    {
        $user = $request->user();
        $employee = new EmployeeResource($user->employee);
    	return response()->json(['employee' => $employee]);
    }
}
