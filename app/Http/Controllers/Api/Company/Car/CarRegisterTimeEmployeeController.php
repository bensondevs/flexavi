<?php

namespace App\Http\Controllers\Api\Company\Car;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\CarRegisterTimeEmployees\{
    PopulateCarRegisterTimeEmployeesRequest as PopulateRequest,
    AssignCarRegisterTimeEmployeeRequest as AssignRequest,
    SetCarRegisterTimeEmployeeAsDriverRequest as SetAsDriverRequest,
    SetCarRegisterTimeEmployeeOutRequest as SetOutRequest,
    UnassignCarRegisterTimeEmployeeRequest as UnassignRequest
};

use App\Http\Resources\CarRegisterTimeEmployeeResource as Resource;

use App\Repositories\CarRegisterTimeEmployeeRepository as AssignedEmployeeRepository;

class CarRegisterTimeEmployeeController extends Controller
{
    /**
     * Repository container variable
     * 
     * @var \App\Models\CarRegisterTimeEmployee  $assignedEmployee 
     */
    private $assignedEmployee;

    /**
     * Initiate the controller creation
     * 
     * @return void
     */
    public function __construct(AssignedEmployeeRepository $assignedEmployee)
    {
        $this->assignedEmployee = $assignedEmployee;
    }

    /**
     * Populate registered time employees.
     * 
     * @param PopulateRequest  $request 
     * @return  
     */
    public function assignedEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $assignedEmployees = $this->assignedEmployee->all($options, true);
        $assignedEmployees = $this->assignedEmployee->paginate();
        $assignedEmployees = Resource::apiCollection($assignedEmployees);

        return response()->json(['assigned_employees' => $assignedEmployees]);
    }

    /**
     * Assign employee to car register time.
     * 
     * @param AssignRequest  $request 
     * @return  
     */
    public function assignEmployee(AssignRequest $request)
    {
        $time = $request->getCarRegisterTime();
        $this->assignedEmployee->setTime($time);

        $employee = $request->getEmployee();
        $input = $request->validated();
        $this->assignedEmployee->assignEmployee($employee, $input);

        return apiResponse($this->assignedEmployee);
    }

    /**
     * Show assigned employee.
     * 
     * @param FindRequest  $request 
     * @return  
     */
    public function show(FindRequest $request)
    {
        $assignedEmployee = $request->getAssignedEmployee();
        $assignedEmployee = new Resource($assignedEmployee);

        return response()->json(['assigned_employee' => $assignedEmployee]);
    }

    /**
     * Set assigned employee in car register time to driver.
     * 
     * @param SetAsDriverRequest  $request 
     * @return  
     */
    public function setAsDriver(SetAsDriverRequest $request)
    {
        $targetEmployee = $request->getAssignedEmployee();

        $this->assignedEmployee->setModel($targetEmployee);
        $this->assignedEmployee->setAsDriver();
        
        return apiResponse($this->assignedEmployee);
    }

    /**
     * Set car passanger out from the car.
     * 
     * @param SetOutRequest  $request 
     * @return  
     */
    public function setOut(SetOutRequest $request)
    {
        $assignedEmployee = $request->getAssignedEmployee();

        $this->assignedEmployee->setModel($assignedEmployee);
        $this->assignedEmployee->setOut();

        return apiResponse($this->assignedEmployee);
    }

    /**
     * Unassign employee from car register time.
     * 
     * @param UnassignRequest  $request 
     * @return  
     */
    public function unassignEmployee(UnassignRequest $request)
    {
        $assignedEmployee = $request->getAssignedEmployee();
        $this->assignedEmployee->setModel($assignedEmployee);

        $force = $request->input('force');
        $this->assignedEmployee->unassignEmployee($force);

        return apiResponse($this->assignedEmployee);
    }
}
