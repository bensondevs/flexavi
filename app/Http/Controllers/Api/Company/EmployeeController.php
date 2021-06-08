<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Employees\SaveEmployeeRequest as SaveRequest;
use App\Http\Requests\Employees\FindEmployeeRequest as FindRequest;
use App\Http\Requests\Employees\PopulateEmployeesRequest as PopulateRequest;

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

    public function companyEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->all($options);
        $employees = $this->employee->paginate();
        $employees->data = EmployeeResource::collection($employees);

    	return response()->json(['employees' => $employees]);
    }

    public function inviteableEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->inviteables($options);
        $employees = $this->employee->paginate();
        $employees->data = EmployeeResource::collection($employees);

        return response()->json(['employees' => $employees]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee, ['employee' => $employee]);
    }

    public function update(SaveRequest $request)
    {
        $employee = $request->getEmployee();
    	$employee = $this->employee->setModel($employee);
        
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee, ['employee' => $employee]);
    }

    public function delete(FindRequest $request)
    {
        $employee = $request->getEmployee();
    	$this->employee->setModel($employee);
    	$this->employee->delete($request->input('force'));

    	return apiResponse($this->employee);
    }
}
