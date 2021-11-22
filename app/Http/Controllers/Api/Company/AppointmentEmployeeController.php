<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\Employees\{
    PopulateAppointmentEmployeesRequest as PopulateRequest,
    PopulateTrashedAppointmentEmployeesRequest as PopulateTrashedsRequest,
    AssignAppointmentEmployeeRequest as AssignEmployeeRequest,
    UnassignAppointmentEmployeeRequest as UnassignEmployeeRequest
};

use App\Repositories\{ EmployeeRepository, AppointmentRepository };

use App\Http\Resources\AppointmentEmployeeResource;

class AppointmentEmployeeController extends Controller
{
    /**
     * Employee repository class container
     * 
     * @var \App\Repositories\EmployeeRepository
     */
    private $employee;

    /**
     * Appointment repository class container
     * 
     * @var \App\Repositories\AppointmentRepository
     */
    private $appointment;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\EmployeeRepository  $employee
     * @param \App\Repositories\AppointmentRepository  $appointment
     * @return void
     */
    public function __construct(
        EmployeeRepository $employee, 
        AppointmentRepository $appointment
    ) {
        $this->employee = $employee;
        $this->appointment = $appointment;
    }

    /**
     * Populate appointment employees
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $appointmentEmployees = $this->employee->appointmentEmployees($options, true);
        $appointmentEmployees = AppointmentEmployeeResource::apiCollection($appointmentEmployees);

        return response()->json(['appointment_employees' => $appointmentEmployees]);
    }

    /**
     * Assign employee to appointment
     * 
     * @param AssignEmployeeRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function assignEmployee(AssignEmployeeRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->appointment->setModel($appointment);

        $employee = $request->getEmployee();
        $this->appointment->assignEmployee($employee);

        return apiResponse($this->appointment);
    }

    /**
     * Unassign employee from appointment
     * 
     * @param UnassignEmployeeRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unassignEmployee(UnassignEmployeeRequest $request)
    {
        $appointmentEmployee = $request->getAppointmentEmployee();
        $this->appointment->unassignEmployee($appointmentEmployee);

        return apiResponse($this->appointment);
    }
}
