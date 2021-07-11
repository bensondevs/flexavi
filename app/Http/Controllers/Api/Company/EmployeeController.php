<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Employees\SaveEmployeeRequest as SaveRequest;
use App\Http\Requests\Employees\FindEmployeeRequest as FindRequest;
use App\Http\Requests\Employees\DeleteEmployeeRequest as DeleteRequest;
use App\Http\Requests\Employees\RestoreEmployeeRequest as RestoreRequest;
use App\Http\Requests\Employees\PopulateEmployeesRequest as PopulateRequest;

use App\Http\Resources\EmployeeResource;

use App\Models\Company;

use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{
    protected $employee;
    protected $company;

    public function __construct(EmployeeRepository $employee)
    {
    	$this->employee = $employee;
    }

    public function companyEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->all($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

    	return response()->json(['employees' => $employees]);
    }

    public function inviteableEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->inviteables($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

        return response()->json(['employees' => $employees]);
    }

    public function trashedEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->trasheds($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

        return response()->json(['employees' => $employees]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee);
    }

    public function update(SaveRequest $request)
    {
        $employee = $request->getEmployee();
    	$employee = $this->employee->setModel($employee);
        
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee);
    }

    public function delete(DeleteRequest $request)
    {
        $employee = $request->getEmployee();
    	$this->employee->setModel($employee);

        $force = strtobool($request->input('force'));
    	$this->employee->delete($force);

    	return apiResponse($this->employee);
    }

    public function restore(RestoreRequest $request)
    {
        $employee = $request->getTrashedEmployee();
        $employee = $this->employee->setModel($employee);
        $employee = $this->employee->restore();

        return apiResponse($this->employee, ['employee' => $employee]);
    }
}