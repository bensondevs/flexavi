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

use App\Repositories\EmployeeRepository;
use App\Repositories\AppointmentRepository;

use App\Http\Resources\AppointmentEmployeeResource;

class AppointmentEmployeeController extends Controller
{
    private $employee;
    private $appointment;

    public function __construct(EmployeeRepository $employee, AppointmentRepository $appointment)
    {
        $this->employee = $employee;
        $this->appointment = $appointment;
    }

    public function appointmentEmployees(PopulateRequest $request)
    {
        $options = $request->options();

        $appointmentEmployees = $this->employee->appointmentEmployees($options, true);
        $appointmentEmployees = AppointmentEmployeeResource::apiCollection($appointmentEmployees);

        return response()->json(['appointment_employees' => $appointmentEmployees]);
    }

    /*public function trashedAppointmentEmployees(PopulateTrashedsRequest $request)
    {
        $options = $request->options();

        $appointmentEmployees = $this->employee->trashedAppointmentEmployees($options, true);
        $appointmentEmployees = AppointmentEmployeeResource::apiCollection($appointmentEmployees);

        return response()->json(['appointment_employees' => $appointmentEmployees]);
    }*/

    public function assignEmployee(AssignEmployeeRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->appointment->setModel($appointment);

        $employee = $request->getEmployee();
        $this->appointment->assignEmployee($employee);

        return apiResponse($this->appointment);
    }

    public function unassignEmployee(UnassignEmployeeRequest $request)
    {
        $appointmentEmployee = $request->getAppointmentEmployee();
        $this->appointment->unassignEmployee($appointmentEmployee);

        return apiResponse($this->appointment);
    }
}
