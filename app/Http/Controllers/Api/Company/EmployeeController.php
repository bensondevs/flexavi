<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Employees\SaveEmployeeRequest;
use App\Http\Requests\Employees\FindEmployeeRequest;
use App\Http\Requests\Employees\PopulateEmployeesRequest;

use App\Http\Resources\EmployeeResource;

use App\Models\Company;

use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{
    protected $employee;
    protected $company;

    public function __construct(
    	EmployeeRepository $employeeRepository
    )
    {
    	$this->employee = $employeeRepository;
    }

    public function companyEmployees(PopulateEmployeesRequest $request)
    {
        $employees = $this->employee->all($request->options());
        $employees = $this->employee->paginate();
        $employees->data = EmployeeResource::collection($employees);

    	return response()->json(['employees' => $employees]);
    }

    public function store(SaveEmployeeRequest $request)
    {
    	$employee = $this->employee->save(
    		$request->ruleWithCompany()
    	);

    	return apiResponse($this->employee, $employee);
    }

    public function update(SaveEmployeeRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->setModel($request->getEmployee());
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee, $employee);
    }

    public function delete(FindEmployeeRequest $request)
    {
    	$this->employee->setModel($request->getEmployee());
    	$this->employee->delete($request->input('force'));

    	return apiResponse(
    		$this->employee, 
    		$this->employee->getModel()
    	);
    }
}
