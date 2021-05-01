<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function companyEmployees(Request $request)
    {
    	return response()->json([
    		'employees' => $this->employee->ofCompany(
    			$company->id
    		),
    	]);
    }

    public function store(SaveEmployeeRequest $request)
    {
    	$employee = $this->employee->save(
    		$request->onlyInRules()
    	);

    	return apiResponse(
    		$this->employee, 
    		$employee
    	);
    }

    public function update(SaveEmployeeRequest $request)
    {
    	$this->employee->setModel($request->getEmployee());
    	$this->employee->save($request->onlyInRules());

    	return apiResponse(
    		$this->employee, 
    		$this->employee->getModel()
    	);
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
