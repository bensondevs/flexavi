<?php

namespace App\Http\Controllers\Api\Company\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CarRegisterTimeEmployees\{AssignCarRegisterTimeEmployeeRequest as AssignRequest};
use App\Http\Requests\Company\CarRegisterTimeEmployees\FindCarRegisterTimeEmployeesRequest as FindRequest;
use App\Http\Requests\Company\CarRegisterTimeEmployees\PopulateCarRegisterTimeEmployeesRequest as PopulateRequest;
use App\Http\Requests\Company\CarRegisterTimeEmployees\SetCarRegisterTimeEmployeeAsDriverRequest as SetAsDriverRequest;
use App\Http\Requests\Company\CarRegisterTimeEmployees\SetCarRegisterTimeEmployeeOutRequest as SetOutRequest;
use App\Http\Requests\Company\CarRegisterTimeEmployees\UnassignCarRegisterTimeEmployeeRequest as UnassignRequest;
use App\Http\Resources\Car\CarRegisterTimeEmployeeResource;
use App\Repositories\Car\CarRegisterTimeEmployeeRepository;

class CarRegisterTimeEmployeeController extends Controller
{
    /**
     * Repository container variable
     *
     * @var CarRegisterTimeEmployeeRepository
     */
    private CarRegisterTimeEmployeeRepository $assignedEmployee;

    /**
     * Initiate the controller creation
     *
     * @return void
     */
    public function __construct(
        CarRegisterTimeEmployeeRepository $assignedEmployee
    )
    {
        $this->assignedEmployee = $assignedEmployee;
    }

    /**
     * Populate registered time employees.
     *
     * @param PopulateRequest $request
     * @return
     */
    public function assignedEmployees(PopulateRequest $request)
    {
        $options = $request->options();
        $assignedEmployees = $this->assignedEmployee->all($options);
        $assignedEmployees = $this->assignedEmployee->paginate(
            $options['per_page']
        );

        return response()->json([
            'assigned_employees' => CarRegisterTimeEmployeeResource::apiCollection(
                $assignedEmployees
            ),
        ]);
    }

    /**
     * Show assigned employee.
     *
     * @param FindRequest $request
     * @return
     */
    public function show(FindRequest $request)
    {
        $assignedEmployee = $request
            ->getAssignedEmployee()
            ->load($request->relations());

        return response()->json([
            'assigned_employee' => new CarRegisterTimeEmployeeResource(
                $assignedEmployee
            ),
        ]);
    }

    /**
     * Assign employee to car register time.
     *
     * @param AssignRequest $request
     * @return
     */
    public function assignEmployee(AssignRequest $request)
    {
        $this->assignedEmployee->setTime($request->getCarRegisterTime());
        $assignedEmployee = $this->assignedEmployee->assignEmployee(
            $request->getEmployee(),
            $request->validated()
        );

        return apiResponse($this->assignedEmployee, [
            'assigned_employee' => new CarRegisterTimeEmployeeResource(
                $assignedEmployee
            ),
        ]);
    }

    /**
     * Set assigned employee in car register time to driver.
     *
     * @param SetAsDriverRequest $request
     * @return
     */
    public function setAsDriver(SetAsDriverRequest $request)
    {
        $this->assignedEmployee->setModel($request->getAssignedEmployee());
        $assignedEmployee = $this->assignedEmployee->setAsDriver();

        return apiResponse($this->assignedEmployee, [
            'assigned_employee' => new CarRegisterTimeEmployeeResource(
                $assignedEmployee
            ),
        ]);
    }

    /**
     * Set car passanger out from the car.
     *
     * @param SetOutRequest $request
     * @return
     */
    public function setOut(SetOutRequest $request)
    {
        $this->assignedEmployee->setModel($request->getAssignedEmployee());
        $assignedEmployee = $this->assignedEmployee->setOut();

        return apiResponse($this->assignedEmployee, [
            'assigned_employee' => new CarRegisterTimeEmployeeResource(
                $assignedEmployee
            ),
        ]);
    }

    /**
     * Unassign employee from car register time.
     *
     * @param UnassignRequest $request
     * @return
     */
    public function unassignEmployee(UnassignRequest $request)
    {
        $this->assignedEmployee->setModel($request->getAssignedEmployee());
        $this->assignedEmployee->unassignEmployee(
            strtobool($request->input('force'))
        );

        return apiResponse($this->assignedEmployee);
    }
}
