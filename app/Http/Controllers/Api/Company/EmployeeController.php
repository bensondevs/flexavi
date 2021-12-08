<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Employees\{
    SaveEmployeeRequest as SaveRequest,
    FindEmployeeRequest as FindRequest,
    DeleteEmployeeRequest as DeleteRequest,
    RestoreEmployeeRequest as RestoreRequest,
    PopulateEmployeesRequest as PopulateRequest
};
use App\Http\Resources\EmployeeResource;

use App\Models\Company;
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
     * Populate company employees
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->all($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

    	return response()->json(['employees' => $employees]);
    }

    /**
     * Populate Company Inviteable employees
     * Employee that does not have controlling user
     * 
     * @param PopulateRequst  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function inviteableEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->inviteables($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

        return response()->json(['employees' => $employees]);
    }

    /**
     * Populate soft-deleted employees
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $employees = $this->employee->trasheds($options);
        $employees = $this->employee->paginate($options['per_page']);
        $employees = EmployeeResource::apiCollection($employees);

        return response()->json(['employees' => $employees]);
    }

    /**
     * Store employee
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee);
    }

    /**
     * View employee with relationships related to it
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $employee = $request->getEmployee();
        $employee->load(['user', 'addresses', 'company']);
        $employee = new EmployeeResource($employee);

        return response()->json(['employee' => $employee]);
    }

    /**
     * Update employee
     * 
     * @param SaveRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $employee = $request->getEmployee();
    	$employee = $this->employee->setModel($employee);
        
        $input = $request->ruleWithCompany();
    	$employee = $this->employee->save($input);

    	return apiResponse($this->employee);
    }

    /**
     * Delete employee
     * 
     * @param  DeleteRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $employee = $request->getEmployee();
    	$this->employee->setModel($employee);

        $force = strtobool($request->input('force'));
    	$this->employee->delete($force);

    	return apiResponse($this->employee);
    }

    /**
     * Restore employee
     * 
     * @param  RestoreRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $employee = $request->getTrashedEmployee();
        $employee = $this->employee->setModel($employee);
        $employee = $this->employee->restore();
        $employee = new EmployeeResource($employee);

        return apiResponse($this->employee, ['employee' => $employee]);
    }
}